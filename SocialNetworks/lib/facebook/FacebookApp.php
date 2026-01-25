<?php
/**
 * Copyright 2017 Facebook, Inc.
 *
 * You are hereby granted a non-exclusive, worldwide, royalty-free license to
 * use, copy, modify, and distribute this software in source code or binary
 * form for use in connection with the web services and APIs provided by
 * Facebook.
 *
 * As with any software that integrates with the Facebook platform, your use
 * of this software is subject to the Facebook Developer Principles and
 * Policies [https://developers.facebook.com/policy/]. This copyright notice
 * shall be included in all copies or substantial portions of the software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 *
 */

namespace Facebook;

use Facebook\Authentication\AccessToken;
use Facebook\Exceptions\FacebookSDKException;

class FacebookApp
{
    /**
     * @var string The app ID.
     */
    protected $id;

    /**
     * @var string The app secret.
     */
    protected $secret;

    /**
     * @param string $id
     * @param string $secret
     *
     * @throws FacebookSDKException
     */
    public function __construct($id, $secret)
    {
        if (!is_string($id) && !is_int($id)) {
            throw new FacebookSDKException('The "app_id" must be formatted as a string since many app IDs are greater than PHP_INT_MAX on some systems.');
        }
        $this->id = (string) $id;
        $this->secret = $secret;
    }

    /**
     * Returns the app ID.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the app secret.
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Returns an app access token.
     *
     * @return AccessToken
     */
    public function getAccessToken()
    {
        return new AccessToken($this->id . '|' . $this->secret);
    }

    /**
     * Custom serialization for php 8+.
     *
     * @return array
     */
    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'secret' => $this->secret,
        ];
    }

    /**
     * Custom unserialization for php 8+.
     *
     * @param array $data
     */
    public function __unserialize(array $data): void
    {
        $this->id = $data['id'];
        $this->secret = $data['secret'];
    }
}

// Testing | Remove when done testing

$app = new FacebookApp('123456', 'abcdef');

// Serialize
$serialized = serialize($app);
echo $serialized . PHP_EOL;

// Unserialize
$unserialized = unserialize($serialized);
echo 'App ID: ' . $unserialized->getId() . PHP_EOL;
echo 'App Secret: ' . $unserialized->getSecret() . PHP_EOL;

// Testing | Remove when done testing
?>
