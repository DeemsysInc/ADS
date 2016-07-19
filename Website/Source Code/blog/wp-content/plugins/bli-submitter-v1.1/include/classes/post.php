<?php
class BliSubmitterPlugin_Post {

    public $permalink;
    public $postDate;

    public function __construct($permalink='', $postDate='') {

    	$this->permalink = $permalink;
    	$this->postDate = $postDate;
    }

    public function isSentBefore() {

    	$res = BliSubmitterPlugin_PostsSentToBli::get('wp_posts_sent_to_bli',array('url' => $this->permalink));
        if(is_array($res) && !empty($res)) return true;
        return false;
    }

    public function isSendingAllowed() {

        $settings = BliSubmitterPlugin_Settings::getInstance();

        if(strtotime($this->postDate) < $settings->randomNewerThan) return false;

        if($settings->duplicate) return true;

        if(!$this->isSentBefore()) return true;

        $sentToBliObj = BliSubmitterPlugin_PostsSentToBli::get('wp_posts_sent_to_bli',array('url' => $this->permalink),true,'object');

        $resendDelay = $settings->duplicateOlderThan;
        $toCheckAgainstDateTime = BliSubmitterPlugin_Utils::mysql_datetime_before($resendDelay);
 
        if(strtotime($sentToBliObj->sent_date_time) > strtotime($toCheckAgainstDateTime)) {

            $sentToBliObj->remove();
            return true;
        }

    	return false;
    }
}
?>