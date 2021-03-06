<?php

namespace crawleradmin\models;

use crawleradmin\Module;

class Builderindex extends \admin\ngrest\base\Model
{
    public function init()
    {
        $this->on(self::EVENT_BEFORE_INSERT, [$this, 'hashContent']);
        $this->on(self::EVENT_BEFORE_UPDATE, [$this, 'hashContent']);
    }
    
    public function hashContent($event)
    {
        $this->content_hash = md5($this->content);
        $count = self::find()->where(['content_hash' => $this->content_hash])->andWhere(['!=', 'url', $this->url])->count();
        if ($count > 0) {
            $this->is_dublication = 1;
        } else {
            $this->is_dublication = 0;
        }
    }
    
    /* yii model properties */

    public static function tableName()
    {
        return 'crawler_builder_index';
    }

    public function scenarios()
    {
        return [
            'restcreate' => ['url', 'content', 'title', 'language_info', 'url_found_on_page'],
            'restupdate' => ['url', 'content', 'title', 'language_info', 'url_found_on_page'],
            'default' => ['url', 'content', 'title', 'language_info', 'content_hash', 'is_dublication', 'url_found_on_page'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'url' => Module::t('builderindex_url'),
            'title' => Module::t('builderindex_title'),
            'language_info' => Module::t('builderindex_language_info'),
            'content' => Module::t('builderindex_content'),
            'url_found_on_page' => Module::t('builderindex_url_found'),
        ];
    }

    /* custom functions */

    public static function isIndexed($url)
    {
        $model = self::findOne(['url' => $url]);

        if ($model) {
            if ($model->crawled == 1) {
                return true;
            }
        }

        return false;
    }

    public static function findUrl($url)
    {
        return self::findOne(['url' => $url]);
    }

    public static function addToIndex($url, $title = null, $urlFoundOnPage = null)
    {
        $model = self::findOne(['url' => $url]);

        if ($model) {
            return false;
        }

        $model = new self();
        $model->url = $url;
        $model->title = $title;
        $model->url_found_on_page = $urlFoundOnPage;
        $model->crawled = 0;

        return $model->save(false);
    }

    /* ngrest model properties */

    public function genericSearchFields()
    {
        return ['url', 'content', 'title', 'language_info'];
    }

    public static function ngRestApiEndpoint()
    {
        return 'api-crawler-builderindex';
    }

    public function ngrestAttributeTypes()
    {
        return [
            'url' => 'text',
            'title' => 'text',
            'language_info' => 'text',
            'url_found_on_page' => 'text',
            'content' => 'textarea',
        ];
    }

    public function ngRestConfig($config)
    {
        $this->ngRestConfigDefine($config, 'list', ['url', 'title', 'language_info', 'url_found_on_page']);
        $this->ngRestConfigDefine($config, ['create', 'update'], ['url', 'title', 'language_info', 'url_found_on_page', 'content']);

        return $config;
    }
}
