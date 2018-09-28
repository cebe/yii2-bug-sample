<?php
require_once  'vendor/autoload.php';

echo PHP_VERSION;
$container = new \yii\di\Container();
define('YII_DEBUG', true);
// Define Yii class.
class Yii extends \yii\BaseYii
{
}

\Yii::$container = $container;
spl_autoload_register(['Yii', 'autoload'], true, false);

(new \yii\console\Application([
    'id' => 'Simple',
    'basePath' => __DIR__,
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'mysql:host=testdb;dbname=test',
            'username' => 'root',
            'password' => 'secret'
        ]
    ],
]))->run();