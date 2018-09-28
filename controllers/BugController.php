<?php
namespace app\controllers;

use app\models\A;
use yii\console\Controller;
use yii\helpers\Console;
use yii\validators\RangeValidator;

/**
 * Database helper commands to manage indexes.
 * @package app\commands
 */
class BugController extends Controller
{
    /** @var \PDO */
    private $pdo;

    private $sql = "show status where `variable_name` = 'Threads_connected';";

    private function dumpCount()
    {
        $this->stdout("Connection count: {$this->pdo->query($this->sql)->fetchColumn(1)}\n", Console::FG_RED);
    }

    private function initWatchPdo()
    {
        $this->pdo = \Yii::$app->db->getMasterPdo();
        \Yii::$app->db->close();
    }
    public function actionIndex()
    {
        $this->initWatchPdo();
        $db = \Yii::$app->db;

        for($i = 0; $i < 100; $i++) {
            $a = A::findOne(['id' => 1]);

            /** @var RangeValidator */
            $rangeValidator = $a->getValidators('mailer_id')[0];
            $rangeValidator->validate(5);
            $db->close();
            $this->dumpCount();
        }

        return;
    }

}