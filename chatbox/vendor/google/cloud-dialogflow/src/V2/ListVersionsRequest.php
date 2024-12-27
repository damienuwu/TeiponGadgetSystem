<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/dialogflow/v2/version.proto

namespace Google\Cloud\Dialogflow\V2;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * The request message for
 * [Versions.ListVersions][google.cloud.dialogflow.v2.Versions.ListVersions].
 *
 * Generated from protobuf message <code>google.cloud.dialogflow.v2.ListVersionsRequest</code>
 */
class ListVersionsRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Required. The agent to list all versions from.
     * Supported formats:
     * - `projects/<Project ID>/agent`
     * - `projects/<Project ID>/locations/<Location ID>/agent`
     *
     * Generated from protobuf field <code>string parent = 1 [(.google.api.field_behavior) = REQUIRED, (.google.api.resource_reference) = {</code>
     */
    private $parent = '';
    /**
     * Optional. The maximum number of items to return in a single page. By
     * default 100 and at most 1000.
     *
     * Generated from protobuf field <code>int32 page_size = 2 [(.google.api.field_behavior) = OPTIONAL];</code>
     */
    private $page_size = 0;
    /**
     * Optional. The next_page_token value returned from a previous list request.
     *
     * Generated from protobuf field <code>string page_token = 3 [(.google.api.field_behavior) = OPTIONAL];</code>
     */
    private $page_token = '';

    /**
     * @param string $parent Required. The agent to list all versions from.
     *                       Supported formats:
     *
     *                       - `projects/<Project ID>/agent`
     *                       - `projects/<Project ID>/locations/<Location ID>/agent`
     *                       Please see {@see VersionsClient::agentName()} for help formatting this field.
     *
     * @return \Google\Cloud\Dialogflow\V2\ListVersionsRequest
     *
     * @experimental
     */
    public static function build(string $parent): self
    {
        return (new self())
            ->setParent($parent);
    }

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $parent
     *           Required. The agent to list all versions from.
     *           Supported formats:
     *           - `projects/<Project ID>/agent`
     *           - `projects/<Project ID>/locations/<Location ID>/agent`
     *     @type int $page_size
     *           Optional. The maximum number of items to return in a single page. By
     *           default 100 and at most 1000.
     *     @type string $page_token
     *           Optional. The next_page_token value returned from a previous list request.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Dialogflow\V2\Version::initOnce();
        parent::__construct($data);
    }

    /**
     * Required. The agent to list all versions from.
     * Supported formats:
     * - `projects/<Project ID>/agent`
     * - `projects/<Project ID>/locations/<Location ID>/agent`
     *
     * Generated from protobuf field <code>string parent = 1 [(.google.api.field_behavior) = REQUIRED, (.google.api.resource_reference) = {</code>
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Required. The agent to list all versions from.
     * Supported formats:
     * - `projects/<Project ID>/agent`
     * - `projects/<Project ID>/locations/<Location ID>/agent`
     *
     * Generated from protobuf field <code>string parent = 1 [(.google.api.field_behavior) = REQUIRED, (.google.api.resource_reference) = {</code>
     * @param string $var
     * @return $this
     */
    public function setParent($var)
    {
        GPBUtil::checkString($var, True);
        $this->parent = $var;

        return $this;
    }

    /**
     * Optional. The maximum number of items to return in a single page. By
     * default 100 and at most 1000.
     *
     * Generated from protobuf field <code>int32 page_size = 2 [(.google.api.field_behavior) = OPTIONAL];</code>
     * @return int
     */
    public function getPageSize()
    {
        return $this->page_size;
    }

    /**
     * Optional. The maximum number of items to return in a single page. By
     * default 100 and at most 1000.
     *
     * Generated from protobuf field <code>int32 page_size = 2 [(.google.api.field_behavior) = OPTIONAL];</code>
     * @param int $var
     * @return $this
     */
    public function setPageSize($var)
    {
        GPBUtil::checkInt32($var);
        $this->page_size = $var;

        return $this;
    }

    /**
     * Optional. The next_page_token value returned from a previous list request.
     *
     * Generated from protobuf field <code>string page_token = 3 [(.google.api.field_behavior) = OPTIONAL];</code>
     * @return string
     */
    public function getPageToken()
    {
        return $this->page_token;
    }

    /**
     * Optional. The next_page_token value returned from a previous list request.
     *
     * Generated from protobuf field <code>string page_token = 3 [(.google.api.field_behavior) = OPTIONAL];</code>
     * @param string $var
     * @return $this
     */
    public function setPageToken($var)
    {
        GPBUtil::checkString($var, True);
        $this->page_token = $var;

        return $this;
    }

}

