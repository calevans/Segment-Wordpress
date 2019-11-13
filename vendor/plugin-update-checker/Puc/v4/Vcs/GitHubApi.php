<?php

if ( ! class_exists( 'Puc_v4_Vcs_GitHubApi', false ) ):

	class Puc_v4_Vcs_GitHubApi extends Puc_v4_Vcs_Api {
		/**
		 * @var string GitHub username.
		 */
		protected $userName;
		/**
		 * @var string GitHub repository name.
		 */
		protected $repositoryName;

		/**
		 * @var string Either a fully qualified repository URL, or just "user/repo-name".
		 */
		protected $repositoryUrl;

		/**
		 * @var string GitHub authentication token. Optional.
		 */
		protected $accessToken;

		public function __construct( $repositoryUrl, $accessToken = null ) {
			$path = @parse_url( $repositoryUrl, PHP_URL_PATH );
			if ( preg_match( '@^/?(?P<username>[^/]+?)/(?P<repository>[^/#?&]+?)/?$@', $path, $matches ) ) {
				$this->userName       = $matches['username'];
				$this->repositoryName = $matches['repository'];
			} else {
				throw new InvalidArgumentException( 'Invalid GitHub repository URL: "' . $repositoryUrl . '"' );
			}

			parent::__construct( $repositoryUrl, $accessToken );
		}

		/**
		 * Get the latest release from GitHub.
		 *
		 * @return Puc_v4_Vcs_Reference|null
		 */
		public function getLatestRelease() {
			$release = $this->api( '/repos/:user/:repo/releases/latest' );
			if ( is_wp_error( $release ) || ! is_object( $release ) || ! isset( $release->tag_name ) ) {
				return null;
			}

			$reference = new Puc_v4_Vcs_Reference( array(
				'name'        => $release->tag_name,
				'version'     => ltrim( $release->tag_name, 'v' ), //Remove the "v" prefix from "v1.2.3".
				'downloadUrl' => $this->signDownloadUrl( $release->zipball_url ),
				'updated'     => $release->created_at,
			) );

			if ( ! empty( $release->body ) ) {
				/** @noinspection PhpUndefinedClassInspection */
				$reference->changelog = Parsedown::instance()->text( $release->body );
			}
			if ( isset( $release->assets[0] ) ) {
				$reference->downloadCount = $release->assets[0]->download_count;
			}

			return $reference;
		}

		/**
		 * Get the tag that looks like the highest version number.
		 *
		 * @return Puc_v4_Vcs_Reference|null
		 */
		public function getLatestTag() {
			$tags = $this->api( '/repos/:user/:repo/tags' );

			if ( is_wp_error( $tags ) || empty( $tags ) || ! is_array( $tags ) ) {
				return null;
			}

			$versionTags = $this->sortTagsByVersion( $tags );
			if ( empty( $versionTags ) ) {
				return null;
			}

			$tag = $versionTags[0];

			return new Puc_v4_Vcs_Reference( array(
				'name'        => $tag->name,
				'version'     => ltrim( $tag->name, 'v' ),
				'downloadUrl' => $this->signDownloadUrl( $tag->zipball_url ),
			) );
		}

		/**
		 * Get a branch by name.
		 *
		 * @param string $branchName
		 *
		 * @return null|Puc_v4_Vcs_Reference
		 */
		public function getBranch( $branchName ) {
			$branch = $this->api( '/repos/:user/:repo/branches/' . $branchName );
			if ( is_wp_error( $branch ) || empty( $branch ) ) {
				return null;
			}

			$reference = new Puc_v4_Vcs_Reference( array(
				'name'        => $branch->name,
				'downloadUrl' => $this->buildArchiveDownloadUrl( $branch->name ),
			) );

			if ( isset( $branch->commit, $branch->commit->commit, $branch->commit->commit->author->date ) ) {
				$reference->updated = $branch->commit->commit->author->date;
			}

			return $reference;
		}

		/**
		 * Get the latest commit that changed the specified file.
		 *
		 * @param string $filename
		 * @param string $ref Reference name (e.g. branch or tag).
		 *
		 * @return StdClass|null
		 */
		public function getLatestCommit( $filename, $ref = 'master' ) {
			$commits = $this->api(
				'/repos/:user/:repo/commits',
				array(
					'path' => $filename,
					'sha'  => $ref,
				)
			);
			if ( ! is_wp_error( $commits ) && is_array( $commits ) && isset( $commits[0] ) ) {
				return $commits[0];
			}

			return null;
		}

		/**
		 * Get the timestamp of the latest commit that changed the specified branch or tag.
		 *
		 * @param string $ref Reference name (e.g. branch or tag).
		 *
		 * @return string|null
		 */
		public function getLatestCommitTime( $ref ) {
			$commits = $this->api( '/repos/:user/:repo/commits', array( 'sha' => $ref ) );
			if ( ! is_wp_error( $commits ) && is_array( $commits ) && isset( $commits[0] ) ) {
				return $commits[0]->commit->author->date;
			}

			return null;
		}

		/**
		 * Perform a GitHub API request.
		 *
		 * @param string $url
		 * @param array $queryParams
		 *
		 * @return mixed|WP_Error
		 */
		protected function api( $url, $queryParams = array() ) {
			$variables = array(
				'user' => $this->userName,
				'repo' => $this->repositoryName,
			);
			foreach ( $variables as $name => $value ) {
				$url = str_replace( '/:' . $name, '/' . urlencode( $value ), $url );
			}
			$url = 'https://api.github.com' . $url;

			if ( ! empty( $this->accessToken ) ) {
				$queryParams['access_token'] = $this->accessToken;
			}
			if ( ! empty( $queryParams ) ) {
				$url = add_query_arg( $queryParams, $url );
			}

			$options = array( 'timeout' => 10 );
			if ( ! empty( $this->httpFilterName ) ) {
				$options = apply_filters( $this->httpFilterName, $options );
			}
			$response = wp_remote_get( $url, $options );
			if ( is_wp_error( $response ) ) {
				return $response;
			}

			$code = wp_remote_retrieve_response_code( $response );
			$body = wp_remote_retrieve_body( $response );
			if ( $code === 200 ) {
				$document = json_decode( $body );

				return $document;
			}

			return new WP_Error(
				'puc-github-http-error',
				'GitHub API error. HTTP status: ' . $code
			);
		}

		/**
		 * Get the contents of a file from a specific branch or tag.
		 *
		 * @param string $path File name.
		 * @param string $ref
		 *
		 * @return null|string Either the contents of the file, or null if the file doesn't exist or there's an error.
		 */
		public function getRemoteFile( $path, $ref = 'master' ) {
			$apiUrl   = '/repos/:user/:repo/contents/' . $path;
			$response = $this->api( $apiUrl, array( 'ref' => $ref ) );

			if ( is_wp_error( $response ) || ! isset( $response->content ) || ( $response->encoding !== 'base64' ) ) {
				return null;
			}

			return base64_decode( $response->content );
		}

		/**
		 * Generate a URL to download a ZIP archive of the specified branch/tag/etc.
		 *
		 * @param string $ref
		 *
		 * @return string
		 */
		public function buildArchiveDownloadUrl( $ref = 'master' ) {
			$url = sprintf(
				'https://api.github.com/repos/%1$s/%2$s/zipball/%3$s',
				urlencode( $this->userName ),
				urlencode( $this->repositoryName ),
				urlencode( $ref )
			);
			if ( ! empty( $this->accessToken ) ) {
				$url = $this->signDownloadUrl( $url );
			}

			return $url;
		}

		/**
		 * Get a specific tag.
		 *
		 * @param string $tagName
		 *
		 * @return Puc_v4_Vcs_Reference|null
		 */
		public function getTag( $tagName ) {
			//The current GitHub update checker doesn't use getTag, so I didn't bother to implement it.
			throw new LogicException( 'The ' . __METHOD__ . ' method is not implemented and should not be used.' );
		}

		public function setAuthentication( $credentials ) {
			parent::setAuthentication( $credentials );
			$this->accessToken = is_string( $credentials ) ? $credentials : null;
		}

		/**
		 * Figure out which reference (i.e tag or branch) contains the latest version.
		 *
		 * @param string $configBranch Start looking in this branch.
		 *
		 * @return null|Puc_v4_Vcs_Reference
		 */
		public function chooseReference( $configBranch ) {
			$updateSource = null;

			if ( $configBranch === 'master' ) {
				//Use the latest release.
				$updateSource = $this->getLatestRelease();
				if ( $updateSource === null ) {
					//Failing that, use the tag with the highest version number.
					$updateSource = $this->getLatestTag();
				}
			}
			//Alternatively, just use the branch itself.
			if ( empty( $updateSource ) ) {
				$updateSource = $this->getBranch( $configBranch );
			}

			return $updateSource;
		}

		/**
		 * @param string $url
		 *
		 * @return string
		 */
		public function signDownloadUrl( $url ) {
			if ( empty( $this->credentials ) ) {
				return $url;
			}

			return add_query_arg( 'access_token', $this->credentials, $url );
		}

	}

endif;