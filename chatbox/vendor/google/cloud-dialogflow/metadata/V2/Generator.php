<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/dialogflow/v2/generator.proto

namespace GPBMetadata\Google\Cloud\Dialogflow\V2;

class Generator
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        \GPBMetadata\Google\Api\Annotations::initOnce();
        \GPBMetadata\Google\Api\Client::initOnce();
        \GPBMetadata\Google\Api\FieldBehavior::initOnce();
        \GPBMetadata\Google\Api\Resource::initOnce();
        \GPBMetadata\Google\Protobuf\GPBEmpty::initOnce();
        \GPBMetadata\Google\Protobuf\FieldMask::initOnce();
        \GPBMetadata\Google\Protobuf\Timestamp::initOnce();
        $pool->internalAddGeneratedFile(
            '
�&
*google/cloud/dialogflow/v2/generator.protogoogle.cloud.dialogflow.v2google/api/client.protogoogle/api/field_behavior.protogoogle/api/resource.protogoogle/protobuf/empty.proto google/protobuf/field_mask.protogoogle/protobuf/timestamp.proto"�
CreateGeneratorRequestC
parent (	B3�A�A-
+cloudresourcemanager.googleapis.com/Project=
	generator (2%.google.cloud.dialogflow.v2.GeneratorB�A
generator_id (	B�A"P
GetGeneratorRequest9
name (	B+�A�A%
#dialogflow.googleapis.com/Generator"�
ListGeneratorsRequestC
parent (	B3�A�A-
+cloudresourcemanager.googleapis.com/Project
	page_size (B�A

page_token (	B�A"l
ListGeneratorsResponse9

generators (2%.google.cloud.dialogflow.v2.Generator
next_page_token (	"S
DeleteGeneratorRequest9
name (	B+�A�A%
#dialogflow.googleapis.com/Generator"�
UpdateGeneratorRequest=
	generator (2%.google.cloud.dialogflow.v2.GeneratorB�A4
update_mask (2.google.protobuf.FieldMaskB�A"�
MessageEntry@
role (2-.google.cloud.dialogflow.v2.MessageEntry.RoleB�A
text (	B�A
language_code (	B�A4
create_time (2.google.protobuf.TimestampB�A"P
Role
ROLE_UNSPECIFIED 
HUMAN_AGENT
AUTOMATED_AGENT
END_USER"]
ConversationContextF
message_entries (2(.google.cloud.dialogflow.v2.MessageEntryB�A"q
SummarizationSectionListU
summarization_sections (20.google.cloud.dialogflow.v2.SummarizationSectionB�A"�
FewShotExampleR
conversation_context (2/.google.cloud.dialogflow.v2.ConversationContextB�AR

extra_info (29.google.cloud.dialogflow.v2.FewShotExample.ExtraInfoEntryB�AZ
summarization_section_list (24.google.cloud.dialogflow.v2.SummarizationSectionListH D
output (2/.google.cloud.dialogflow.v2.GeneratorSuggestionB�A0
ExtraInfoEntry
key (	
value (	:8B
instruction_list"�
InferenceParameter#
max_output_tokens (B�AH �
temperature (B�AH�
top_k (B�AH�
top_p (B�AH�B
_max_output_tokensB
_temperatureB
_top_kB
_top_p"�
SummarizationSection
key (	B�A

definition (	B�AH
type (25.google.cloud.dialogflow.v2.SummarizationSection.TypeB�A"�
Type
TYPE_UNSPECIFIED 
	SITUATION

ACTION

RESOLUTION
REASON_FOR_CANCELLATION
CUSTOMER_SATISFACTION
ENTITIES
CUSTOMER_DEFINED"�
SummarizationContextU
summarization_sections (20.google.cloud.dialogflow.v2.SummarizationSectionB�AJ
few_shot_examples (2*.google.cloud.dialogflow.v2.FewShotExampleB�A
version (	B�A!
output_language_code (	B�A"�
	Generator
name (	B�A�A
description (	B�AQ
summarization_context (20.google.cloud.dialogflow.v2.SummarizationContextH P
inference_parameter (2..google.cloud.dialogflow.v2.InferenceParameterB�AD
trigger_event (2(.google.cloud.dialogflow.v2.TriggerEventB�A4
create_time (2.google.protobuf.TimestampB�A4
update_time	 (2.google.protobuf.TimestampB�A:�A|
#dialogflow.googleapis.com/Generator>projects/{project}/locations/{location}/generators/{generator}*
generators2	generatorB	
context"�
SummarySuggestion[
summary_sections (2<.google.cloud.dialogflow.v2.SummarySuggestion.SummarySectionB�A<
SummarySection
section (	B�A
summary (	B�A"u
GeneratorSuggestionP
summary_suggestion (2-.google.cloud.dialogflow.v2.SummarySuggestionB�AH B

suggestion*T
TriggerEvent
TRIGGER_EVENT_UNSPECIFIED 
END_OF_UTTERANCE
MANUAL_CALL2�	

Generators�
CreateGenerator2.google.cloud.dialogflow.v2.CreateGeneratorRequest%.google.cloud.dialogflow.v2.Generator"��Aparent,generator,generator_id���l"./v2/{parent=projects/*/locations/*}/generators:	generatorZ/""/v2/{parent=projects/*}/generators:	generator�
GetGenerator/.google.cloud.dialogflow.v2.GetGeneratorRequest%.google.cloud.dialogflow.v2.Generator"=�Aname���0./v2/{name=projects/*/locations/*/generators/*}�
ListGenerators1.google.cloud.dialogflow.v2.ListGeneratorsRequest2.google.cloud.dialogflow.v2.ListGeneratorsResponse"e�Aparent���V./v2/{parent=projects/*/locations/*}/generatorsZ$"/v2/{parent=projects/*}/generators�
DeleteGenerator2.google.cloud.dialogflow.v2.DeleteGeneratorRequest.google.protobuf.Empty"=�Aname���0*./v2/{name=projects/*/locations/*/generators/*}�
UpdateGenerator2.google.cloud.dialogflow.v2.UpdateGeneratorRequest%.google.cloud.dialogflow.v2.Generator"c�Agenerator,update_mask���E28/v2/{generator.name=projects/*/locations/*/generators/*}:	generatorx�Adialogflow.googleapis.com�AYhttps://www.googleapis.com/auth/cloud-platform,https://www.googleapis.com/auth/dialogflowB�
com.google.cloud.dialogflow.v2BGeneratorProtoPZ>cloud.google.com/go/dialogflow/apiv2/dialogflowpb;dialogflowpb��DF�Google.Cloud.Dialogflow.V2bproto3'
        , true);

        static::$is_initialized = true;
    }
}

