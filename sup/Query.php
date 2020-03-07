<?php
class Query
{
  private $dbName;
  function __construct($name)
  {
    $this->dbName = $name;
  }
  function setDbName($name)
  {
    $this->dbName = $name;
  }

  function updateQueryById($colNames)
  {
    $query = "UPDATE `$this->dbName` SET ";
    for ($i = 0; $i < count($colNames); $i++) {
      if ($i === 0) {
        $query .= "`$colNames[$i]`=(?)";
      } else {
        $query .= ",`$colNames[$i]`=(?)";
      }
    }
    $query .= " WHERE `id` = (?)";
    return $query;
  }
  function selectQueryById($colNames = null)
  {
    $query = 'SELECT ';
    if ($colNames) {
      $is_first = true;
      foreach ($colNames as $name) {
        if ($is_first) {
          $query .= "`$name`";
          $is_first = false;
        } else {
          $query .= ",`$name`";
        }
      }
      $query .= " FROM `$this->dbName` WHERE `id` = (?)";
    } else {
      $query .= "* FROM `$this->dbName` WHERE `id`=(?)";
    }
    return $query;
  }
  function insertQuery($colNames)
  {
    // example query
    // "INSERT INTO `events`(`id`, `cid`, `title`, `content`, `location`, `date`, `img01`, `img02`, `img03`, `img04`, `img05`, `img06`, `img07`, `update_at`, `create_at`) VALUES ()";
    $query = "INSERT INTO `$this->dbName`(";
    for ($i = 0; $i < count($colNames); $i++) {
      if ($i === 0) {
        $query .= "`$colNames[$i]`";
      } else {
        $query .= ",`$colNames[$i]`";
      }
    }
    $query .= ") VALUES (";
    for ($i = 0; $i < count($colNames); $i++) {
      if ($i === 0) {
        $query .= "?";
      } else {
        $query .= ",?";
      }
    }
    $query .= ")";
    return $query;
  }
  function deleteQueryById()
  {
    // example query
    // "DELETE FROM `events` WHERE `id` = ?";
    $query = "DELETE FROM `$this->dbName` WHERE `id` = (?)";
    return $query;
  }
}
