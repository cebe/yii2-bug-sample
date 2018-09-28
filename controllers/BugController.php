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
            $rangeValidator = $a->getValidators()[0];
            $rangeValidator->validate(5);
            $db->close();
            $this->dumpCount();
        }

        return;
    }

    /**
     * Try to reproduce the bug without Yii AR
     */
    public function actionAlternative()
    {
        $this->initWatchPdo();
        $db = \Yii::$app->db;

        for($i = 0; $i< 100; $i++) {

            $statement = $db->masterPdo->prepare('select * from `b`');
            $statement->setFetchMode(\PDO::FETCH_ASSOC);
            $holder = new \ArrayObject();
            $holder->append($statement);

            $circular = new \ArrayObject();
            $circular->append($holder);
            $holder->append($circular);
            $db->close();
            $this->dumpCount();
        }



    }

}