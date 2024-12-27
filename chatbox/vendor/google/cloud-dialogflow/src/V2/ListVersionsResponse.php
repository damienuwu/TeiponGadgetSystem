<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/dialogflow/v2/version.proto

namespace Google\Cloud\Dialogflow\V2;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * The response message for
 * [Versions.ListVersions][google.cloud.dialogflow.v2.Versions.ListVersions].
 *
 * Generated from protobuf message <code>google.cloud.dialogflow.v2.ListVersionsResponse</code>
 */
class ListVersionsResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * The list of agent versions. There will be a maximum number of items
     * returned based on the page_size field in the request.
     *
     * Generated from protobuf field <code>repeated .google.cloud.dialogflow.v2.Version versions = 1;</code>
     */
    private $versions;
    /**
     * Token to retrieve the next page of results, or empty if there are no
     * more results in the list.
     *
     * Generated from protobuf field <code>string next_page_token = 2;</code>
     */
    private $next_page_token = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type array<\Google\Cloud\Dialogflow\V2\Version>|\Google\Protobuf\Internal\RepeatedField $versions
     *           The list of agent versions. There will be a maximum number of items
     *           returned based on the page_size field in the request.
     *     @type string $next_page_token
     *           Token to retrieve the next page of results, or empty if there are no
     *           more results in the list.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Dialogflow\V2\Version::initOnce();
        parent::__construct($data);
    }

    /**
     * The list of agent versions. There will be a maximum number of items
     * returned based on the page_size field in the request.
     *
     * Generated from protobuf field <code>repeated .google.cloud.dialogflow.v2.Version versions = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getVersions()
    {
        return $this->versions;
    }

    /**
     * The list of agent versions. There will be a maximum number of items
     * returned based on the page_size field in the request.
     *
     * Generated from protobuf field <code>repeated .google.cloud.dialogflow.v2.Version versions = 1;</code>
     * @param array<\Google\Cloud\Dialogflow\V2\Version>|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setVersions($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Google\Cloud\Dialogflow\V2\Version::class);
        $this->versions = $arr;

        return $this;
    }

    /**
     * Token to retrieve the next page of results, or empty if there are no
     * more results in the list.
     *
     * Generated from protobuf field <code>string next_page_token = 2;</code>
     * @return string
     */
    public function getNextPageToken()
    {
        return $this->next_page_token;
    }

    /**
     * Token to retrieve the next page of results, or empty if there are no
     * more results in the list.
     *
     * Generated from protobuf field <code>string next_page_token = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setNextPageToken($var)
    {
        GPBUtil::checkString($var, True);
        $this->next_page_token = $var;

        return $this;
    }

}

