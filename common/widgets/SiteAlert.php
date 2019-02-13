<?php
namespace common\widgets;

use Yii;
use yii\helpers\Html;
use frontend\models\Alert;
use yii\db\Expression;

/**
 * Alert widget renders a sitewide message for a specified amount of time
 * Message is set by admins. Message is displayed using: http://kenwheeler.github.io/slick/
 *
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class SiteAlert extends \yii\bootstrap4\Widget
{
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - key: the name of the session flash variable
     * - value: the bootstrap alert type (i.e. danger, success, info, warning)
     */
    public $alertTypes = [
        'error'   => 'alert-danger',
        'danger'  => 'alert-danger',
        'success' => 'alert-success',
        'info'    => 'alert-info',
        'warning' => 'alert-warning'
    ];
    /**
     * @var array the options for rendering the close button tag.
     * Array will be passed to [[\yii\bootstrap\Alert::closeButton]].
     */
    public $closeButton = [];


    /**
     * {@inheritdoc}
     */
    public function run()
    {
        //load alerts
        //SELECT * FROM `alert` WHERE NOW() <= end_dt and NOW() >= start_dt;
        $sd = new Expression('start_dt');
        $ed = new Expression('end_dt');
        $alertModel = Alert::find()
            ->where(['between', 'NOW()', $sd, $ed ])->asArray()->all();
        
        //package results into groups
        $alertsByGroup = [];
        foreach ($alertModel as $alert) {
            $group = $alert['group'];
            $type = $alert['type'];
            $alertsByGroup[$group][$type] = $alert;
        }

        foreach ($alertsByGroup as $group => $alerts) {
            $title = '';
            switch ($group) {
                case 'rec':
                    $title = 'Message from: Sibley Recreation Department';
                    break;
                case 'city':
                    $title = 'Message from: City of Sibley';
                    break;
                case 'chamber':
                    $title = 'Message from: Sibley Chamber of Commerce';
                    break;
            }
            echo $this->render('alert/slickAlert', [
                'alerts' => $alerts,
                'group' => $group,
                'title' => $title
            ]);
        }
    }
}

        //$appendClass = isset($this->options['class']) ? ' ' . $this->options['class'] : '';
        //$html = '<div class="siteWideAlert">';
/*
            foreach ((array) $flash as $i => $message) {
                echo \yii\bootstrap4\Alert::widget([
                    'body' => $message,
                    'closeButton' => $this->closeButton,
                    'options' => array_merge($this->options, [
                        'id' => $this->getId() . '-' . $type . '-' . $i,
                        'class' => $this->alertTypes[$type] . $appendClass,
                    ]),
                ]);
            }
*/
