<?php
/**
 * Created by PhpStorm.
 * User: josesambrano
 * Date: 7/20/15
 * Time: 7:52 PM
 */

namespace Pkj\LinuxGenericBackup;

class BackupRegistro
{
     private $pdo;

     private $fpdo;

     private $table;

     public function __construct(){
          // cargar configuracion
          $datos=(array)FormatUtil::safeUnserialize(file_get_contents('config/custom.json'));
          $tablas =(array)$datos['database']->tablas;
          $this->table= $tablas['backup'];
          $this->pdo = new \PDO("pgsql:dbname=".$datos['database']->database.";host=".$datos['database']->host, $datos['database']->user, $datos['database']->password);
          $this->fpdo = new \FluentPDO($this->pdo);
     }

     public function saveProcess(Proceso $model){
          $values = array('size' => $model->size, 'date_created' => date('Y-m-d H:i:s'),'files'=>json_encode($model->files),'action'=>$model->action,'process_type'=>$model->process_type);
          $query = $this->fpdo->insertInto($this->table)->values($values);

          return $query->execute();
     }


}