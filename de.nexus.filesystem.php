<?php
class filesystem_nexus_de{
 public $date = "1541856873";
 public $version = "1.0.0";
 private $src = "";
 private $data;
 private $file = "";
 private $folder = "";
 private $path = "";
 private $basepath = "";
 private $index = array();
 private $info = array();
 private $sign = "";
 private $comments = "";
 private $auto = false;
 private $cfg;
 public $st = array(
   "full" => SORT_NATURAL,
   "name" => SORT_NATURAL,
   "ext" => SORT_STRING,
   "path" => SORT_STRING,
   "folder" => SORT_STRING,
   "type" => SORT_STRING,
   "right" => SORT_STRING,
   "target" => SORT_STRING,
   "mime" => SORT_STRING,
   "dev" => SORT_NUMERIC,
   "ino" => SORT_NUMERIC,
   "mode" => SORT_NUMERIC,
   "nlink" => SORT_NUMERIC,
   "uid" => SORT_NUMERIC,
   "gid" => SORT_NUMERIC,
   "rdev" => SORT_NUMERIC,
   "size" => SORT_NUMERIC,
   "atime" => SORT_NUMERIC,
   "mtime" => SORT_NUMERIC,
   "ctime" => SORT_NUMERIC,
   "blksize" => SORT_NUMERIC,
   "blocks" => SORT_NUMERIC
 );

 public function __construct($name = "", $path = "", $auto = false, $cfg = ""){
  if(is_string($path) && $path != ""){
   $this->setPath($path);
  }
  if(is_string($name) && $name != ""){
   $this->setFile($name);
  }
  if(is_bool($auto) && $auto == true){
   $this->auto = $auto;
  }
 }
 public function __destruct(){
  if(is_bool($this->auto) && $this->auto == true){
   
  }
  clearstatcache();
 }
 public function getSrc(){
  return $this->src;
 }
 public function setSrc($src){
  $this->src = $src;
 }
 public function getData(){
  return $this->data;
 }
 public function setData($data){
  $this->data = $data;
 }
 public function getFile(){
  return $this->file;
 }
 public function setFile($name = ""){
  if(is_string($name)){
   $this->file = $name;
  }
 }
 public function getDirectory(){
  return $this->folder;
 }
 public function getPath(){
  return $this->path;
 }
 public function setPath($name = ""){
  if(is_string($name) && $name != "" && is_dir(realpath(".")."/".$name)){
   $rp = realpath(".");
   $this->basepath = $rp;
   $this->path = $rp."/".$name;
   $this->path = realpath($this->path);
   $this->folder = basename($this->path);
   if(substr($this->path, 0, strlen($rp)) != $rp){
    $this->path = $rp;
    $this->folder = basename($this->path);
   }
  }
 }
 public function getInfo(){
  return $this->info;
 }
 public function getComments(){
  return $this->comments;
 }
 public function setSign($sign){
  $this->sign = $sign;
 }
 
 public function file_txt_read_sign($sign = true, $skip = 0){
  if(!(is_bool($sign)||is_int($sign))||!is_int($skip)) return false;
  if(($this->path == "")||($this->file == "")) return false;
  $p = $this->vali_path($this->path);
  $f = $this->vali_path($this->path."/".$this->file);
  if(!$this->perms($p, "dr")) return false;
  if(!$this->perms($f, "fr")) return false;
  $data = "";
  $r = true;
  $fp = fopen($f, "r");
  if($fp == false) $r = false;
  if($r == true){
   while(!feof($fp)){
    if($skip <= 0){
     if($sign == true || $sign > 0){
      $data .= fgetc($fp);
      if(is_int($sign)) $sign--;
     }
     else{
      break;
     }
    }
    else{
     fgetc($fp);
     $skip--;
    }
   }
  }
  fclose($fp);
  if($r == true){
   $this->data = $data;
  }
  return $r;
 }
 public function file_txt_read_line($line = true, $skip = 0){
  if(!(is_bool($line)||is_int($line))||!is_int($skip)) return false;
  if(($this->path == "")||($this->file == "")) return false;
  $p = $this->vali_path($this->path);
  $f = $this->vali_path($this->path."/".$this->file);
  if(!$this->perms($p, "dr")) return false;
  if(!$this->perms($f, "fr")) return false;
  $data = array();
  $r = true;
  $fp = fopen($f, "r");
  if($fp == false) $r = false;
  if($r == true){
   while(!feof($fp)){
    if($skip <= 0){
     if($line == true || $line > 0){
      array_push($data, fgets($fp));
      if(is_int($line)) $line--;
     }
     else{
      break;
     }
    }
    else{
     fgets($fp);
     $skip--;
    }
   }
  }
  fclose($fp);
  if($r == true){
   if(count($data) == 1){
    $this->data = $data[0];
   }
   elseif(count($data) > 1){
    $this->data = $data;
   }
  }
  return $r;
 }
 public function file_txt_read_full(){
  if(($this->path == "")||($this->file == "")) return false;
  $p = $this->vali_path($this->path);
  $f = $this->vali_path($this->path."/".$this->file);
  if(!$this->perms($p, "dr")) return false;
  if(!$this->perms($f, "fr")) return false;
  $data = array();
  $r = true;
  $fp = fopen($f, "r");
  if($fp == false) $r = false;
  if($r == true){
   while(!feof($fp)){
    array_push($data, fgets($fp));
   }
  }
  fclose($fp);
  if($r == true){
   if(count($data) == 1){
    $this->data = $data[0];
   }
   elseif(count($data) > 1){
    $this->data = $data;
   }
  }
  return $r;
 }
 public function file_txt_write_sign($sign = true, $skip = 0){
  if(!(is_bool($sign)||is_int($sign))||!is_int($skip)) return false;
  if(($this->path == "")||($this->file == "")) return false;
  $p = $this->vali_path($this->path);
  $f = $this->vali_path($this->path."/".$this->file);
  if(!$this->perms($p, "drw")) return false;
  if($this->perms($f, "-")){
   if(!$this->perms($f, "frw")) return false;
  }
  if((gettype($this->data) != "string")&&(gettype($this->data) != "array")) return false;
  $r = true;
  if($this->perms($f, "f")){
   $fp = fopen($f, "r+");
  }
  else{
   $fp = fopen($f, "w");
  }
  if($fp == false) return false;
  if($skip > 0){
   while($skip > 0 && !feof($fp)){
    fgetc($fp);
    $skip--;
   }
  }
  switch(gettype($this->data)){
  default:
   $r = false;
   break;
  case "string":
   if($sign == true || $sign > 0){
    $sl = strlen($this->data);
    if(!is_bool($sign) && $sl > $sign) $sl = $sign;
    fputs($fp, $this->data, $sl);
   }
   break;
  case "array":
   foreach($this->data as $v){
    if(gettype($v) != "string") break;
    if($sign == true || $sign > 0){
     $sl = strlen($v);
     if(!is_bool($sign) && $sl > $sign) $sl = $sign;
     fputs($fp, $v, $sl);
     if(is_int($sign)) $sign -= $sl;
    }
    else{
     break;
    }
   }
   break;
  }
  fclose($fp);
  return $r;
 }
 public function file_txt_write_line($line = true, $skip = 0){
  if(!(is_bool($line)||is_int($line))||!is_int($skip)) return false;
  if(($this->path == "")||($this->file == "")) return false;
  $p = $this->vali_path($this->path);
  $f = $this->vali_path($this->path."/".$this->file);
  if(!$this->perms($p, "drw")) return false;
  if($this->perms($f, "-")){
   if(!$this->perms($f, "frw")) return false;
  }
  if((gettype($this->data) != "string")&&(gettype($this->data) != "array")) return false;
  $r = true;
  if($this->perms($f, "f")){
   $fp = fopen($f, "r+");
  }
  else{
   $fp = fopen($f, "w");
  }
  if($fp == false) return false;
  if($skip > 0){
   while($skip > 0 && !feof($fp)){
    fgets($fp);
    $skip--;
   }
  }
  switch(gettype($this->data)){
  default:
   $r = false;
   break;
  case "string":
   if($line == true || $line > 0){
    fputs($fp, $this->data);
   }
   break;
  case "array":
   foreach($this->data as $v){
    if(gettype($v) != "string") break;
    if($line == true || $line > 0){
     fputs($fp, $v);
     if(is_int($line)) $line--;
    }
    else{
     break;
    }
   }
   break;
  }
  fclose($fp);
  return $r;
 }
 public function file_txt_write_full(){
  if(($this->path == "")||($this->file == "")) return false;
  $p = $this->vali_path($this->path);
  $f = $this->vali_path($this->path."/".$this->file);
  if(!$this->perms($p, "drw")) return false;
  if($this->perms($f, "-")){
   if(!$this->perms($f, "frw")) return false;
  }
  if((gettype($this->data) != "string")&&(gettype($this->data) != "array")) return false;
  $r = true;
  $fp = fopen($f, "w");
  if($fp == false) return false;
  switch(gettype($this->data)){
  default:
   $r = false;
   break;
  case "string":
   fputs($fp, $this->data);
   break;
  case "array":
   foreach($this->data as $v){
    fputs($fp, $v);
   }
   break;
  }
  fclose($fp);
  return $r;
 }
 public function file_txt_append_sign($pos = "l", $sign = true){
  if(!(is_string($pos)||is_int($pos))||!(is_bool($sign)||is_int($sign))) return false;
  if(($this->path == "")||($this->file == "")) return false;
  $p = $this->vali_path($this->path);
  $f = $this->vali_path($this->path."/".$this->file);
  $t = $this->vali_path($this->path."/".$this->file.".tmp");
  if(!$this->perms($p, "drw")) return false;
  if($this->perms($f, "-")){
   if(!$this->perms($f, "frw")) return false;
  }
  if($this->perms($t, "-")) return false;
  $r = true;
  switch($pos){
  default:
   if(!is_int($pos)) return false;
   $tp = fopen($t, "w");
   $fp = fopen($f, "r");
   if($tp == false){if($fp != false){fclose($fp);} return false;}
   if($fp == false){if($tp != false){fclose($tp);} return false;}
   while(!feof($fp)&&$pos > 0){
    fputs($tp, fgetc($fp));
    $pos--;
   }
   switch(gettype($this->data)){
   default:
    $r = false;
    break;
   case "string":
    if($sign == true || $sign > 0){
     $sl = strlen($this->data);
     if(!is_bool($sign) && $sl > $sign) $sl = $sign;
     fputs($tp, $this->data, $sl);
    }
    break;
   case "array":
    foreach($this->data as $v){
     if(gettype($v) != "string") break;
     if($sign == true || $sign > 0){
      $sl = strlen($v);
      if(!is_bool($sign) && $sl > $sign) $sl = $sign;
      fputs($tp, $v, $sl);
      if(is_int($sign)) $sign -= $sl;
     }
     else{
      break;
     }
    }
    break;
   }
   while(!feof($fp)){
    fputs($tp, fgetc($fp));
   }
   fclose($tp);
   fclose($fp);
   @unlink($f);
   @rename($t, $f);
   break;
  case "f":
   $tp = fopen($t, "w");
   $fp = fopen($f, "r");
   if($tp == false){if($fp != false){fclose($fp);} return false;}
   if($fp == false){if($tp != false){fclose($tp);} return false;}
   switch(gettype($this->data)){
   default:
    $r = false;
    break;
   case "string":
    if($sign == true || $sign > 0){
     $sl = strlen($this->data);
     if(!is_bool($sign) && $sl > $sign) $sl = $sign;
     fputs($tp, $this->data, $sl);
    }
    break;
   case "array":
    foreach($this->data as $v){
     if(gettype($v) != "string") break;
     if($sign == true || $sign > 0){
      $sl = strlen($v);
      if(!is_bool($sign) && $sl > $sign) $sl = $sign;
      fputs($tp, $v, $sl);
      if(is_int($sign)) $sign -= $sl;
     }
     else{
      break;
     }
    }
    break;
   }
   while(!feof($fp)){
    fputs($tp, fgetc($fp));
   }
   fclose($tp);
   fclose($fp);
   @unlink($f);
   @rename($t, $f);
   break;
  case "l":
   $fp = fopen($f, "a");
   if($fp == false) return false;
   switch(gettype($this->data)){
   default:
    $r = false;
    break;
   case "string":
    if($sign == true || $sign > 0){
     $sl = strlen($this->data);
     if(!is_bool($sign) && $sl > $sign) $sl = $sign;
     fputs($fp, $this->data, $sl);
    }
    break;
   case "array":
    foreach($this->data as $v){
     if(gettype($v) != "string") break;
     if($sign == true || $sign > 0){
      $sl = strlen($v);
      if(!is_bool($sign) && $sl > $sign) $sl = $sign;
      fputs($fp, $v, $sl);
      if(is_int($sign)) $sign -= $sl;
     }
     else{
      break;
     }
    }
    break;
   }
   fclose($fp);
   break;
  }
  return $r;
 }
 public function file_txt_append_line($pos = "l", $line = true){
  if(!(is_string($pos)||is_int($pos))||!(is_bool($line)||is_int($line))) return false;
  if(($this->path == "")||($this->file == "")) return false;
  $p = $this->vali_path($this->path);
  $f = $this->vali_path($this->path."/".$this->file);
  $t = $this->vali_path($this->path."/".$this->file.".tmp");
  if(!$this->perms($p, "drw")) return false;
  if($this->perms($f, "-")){
   if(!$this->perms($f, "frw")) return false;
  }
  if($this->perms($t, "-")) return false;
  $r = true;
  switch($pos){
  default:
   if(!is_int($pos)) return false;
   $tp = fopen($t, "w");
   $fp = fopen($f, "r");
   if($tp == false){if($fp != false){fclose($fp);} return false;}
   if($fp == false){if($tp != false){fclose($tp);} return false;}
   while(!feof($fp)&&$pos > 0){
    fputs($tp, fgets($fp));
    $pos--;
   }
   switch(gettype($this->data)){
   default:
    $r = false;
    break;
   case "string":
    if($line == true || $line > 0){
     fputs($tp, $this->data);
    }
    break;
   case "array":
    foreach($this->data as $v){
     if(gettype($v) != "string") break;
     if($line == true || $line > 0){
      fputs($tp, $v);
      if(is_int($line)) $line--;
     }
     else{
      break;
     }
    }
    break;
   }
   while(!feof($fp)){
    fputs($tp, fgets($fp));
   }
   fclose($tp);
   fclose($fp);
   @unlink($f);
   @rename($t, $f);
   break;
  case "f":
   $tp = fopen($t, "w");
   $fp = fopen($f, "r");
   if($tp == false){if($fp != false){fclose($fp);} return false;}
   if($fp == false){if($tp != false){fclose($tp);} return false;}
   switch(gettype($this->data)){
   default:
    $r = false;
    break;
   case "string":
    if($line == true || $line > 0){
     fputs($tp, $this->data);
    }
    break;
   case "array":
    foreach($this->data as $v){
     if(gettype($v) != "string") break;
     if($line == true || $line > 0){
      echo $v;
      fputs($tp, $v);
      if(is_int($line)) $line--;
     }
     else{
      break;
     }
    }
    break;
   }
   while(!feof($fp)){
    fputs($tp, fgets($fp));
   }
   fclose($tp);
   fclose($fp);
   @unlink($f);
   @rename($t, $f);
   break;
  case "l":
   $fp = fopen($f, "a");
   if($fp == false) return false;
   switch(gettype($this->data)){
   default:
    $r = false;
    break;
   case "string":
    if($line == true || $line > 0){
     fputs($fp, $this->data);
    }
    break;
   case "array":
    foreach($this->data as $v){
     if(gettype($v) != "string") break;
     if($line == true || $line > 0){
      fputs($fp, $v);
      if(is_int($line)) $line--;
     }
     else{
      break;
     }
    }
    break;
   }
   fclose($fp);
   break;
  }
  return $r;
 }
 public function file_txt_append_full($pos = "l"){
  if(!is_string($pos)) return false;
  if(($this->path == "")||($this->file == "")) return false;
  $p = $this->vali_path($this->path);
  $f = $this->vali_path($this->path."/".$this->file);
  $t = $this->vali_path($this->path."/".$this->file.".tmp");
  if(!$this->perms($p, "drw")) return false;
  if($this->perms($f, "-")){
   if(!$this->perms($f, "frw")) return false;
  }
  if($this->perms($t, "-")) return false;
  $r = true;
  switch($pos){
  default:
   return false;
   break;
  case "f":
   $tp = fopen($t, "w");
   $fp = fopen($f, "r");
   if($tp == false){if($fp != false){fclose($fp);} return false;}
   if($fp == false){if($tp != false){fclose($tp);} return false;}
   switch(gettype($this->data)){
   default:
    $r = false;
   break;
   case "string":
    fputs($tp, $this->data);
    break;
   case "array":
    foreach($this->data as $v){
     fputs($tp, $v);
    }
    break;
   }
   while(!feof($fp)){
    fputs($tp, fgets($fp));
   }
   fclose($tp);
   fclose($fp);
   @unlink($f);
   @rename($t, $f);
   break;
  case "l":
   $fp = fopen($f, "a");
   if($fp == false) return false;
   switch(gettype($this->data)){
   default:
    $r = false;
    break;
   case "string":
    fputs($fp, $this->data);
    break;
   case "array":
    foreach($this->data as $v){
     fputs($fp, $v);
    }
    break;
   }
   fclose($fp);
   break;
  }
  return $r;
 }
 public function file_txt_erase(){
  return $this->file_erase();
 }
 public function file_txt_copy($new = ""){
  return $this->file_copy($new);
 }
 public function file_txt_move($new = ""){
  return $this->file_move($new);
 }
 public function file_txt_rename($new = ""){
  return $this->file_rename($new);
 }
 public function file_txt_info(){
  return $this->file_info();
 }
 public function file_txt_csv_read_sign($sign = true, $skip = 0){
  if(!$this->file_txt_read_sign($sign, $skip)) return false;
  if(!$this->data_trim("i")) return false;
  return $this->file_txt_csv("i");
 }
 public function file_txt_csv_read_line($line = true, $skip = 0){
  if(!$this->file_txt_read_line($line, $skip)) return false;
  if(!$this->data_trim("i")) return false;
  return $this->file_txt_csv("i");
 }
 public function file_txt_csv_read_full(){
  if(!$this->file_txt_read_full()) return false;
  if(!$this->data_trim("i")) return false;
  return $this->file_txt_csv("i");
 }
 public function file_txt_csv_write_sign($sign = true, $skip = 0){
  if(!$this->file_txt_csv("o")) return false;
  if(!$this->data_trim("o")) return false;
  return $this->file_txt_write_sign($sign, $skip);
 }
 public function file_txt_csv_write_line($line = true, $skip = 0){
  if(!$this->file_txt_csv("o")) return false;
  if(!$this->data_trim("o")) return false;
  return $this->file_txt_write_line($line, $skip);
 }
 public function file_txt_csv_write_full(){
  if(!$this->file_txt_csv("o")) return false;
  if(!$this->data_trim("o")) return false;
  return $this->file_txt_write_full();
 }
 protected function file_txt_csv($io = ""){
  if(!is_string($this->sign)) return false;
  if($io == "i"){
   if(is_string($this->data)){
    $this->data = explode($this->sign, $this->data);
   }
   else{
    $t = array();
    foreach($this->data as $v){
     array_push($t, explode($this->sign, $v));
    }
    $this->data = $t;
   }
   return true;
  }
  elseif($io == "o"){
   if(!is_array($this->data)) return false;
   $t = "";
   foreach($this->data as $v){
    if(is_string($v)){
     $t = "string";
     break;
    }
    else{
     $t = "array";
     break;
    }
   }
   if($t == "string"){
    $this->data = implode($this->sign, $this->data);
   }
   else{
    $t = array();
    foreach($this->data as $v){
     if(is_array($v)){
      $p = true;
      foreach($v as $v1){
       if(!is_string($v1)) $p = false;
      }
      if($p == false) return false;
      array_push($t, implode($this->sign, $v));
     }
    }
    $this->data = $t;
   }
   return true;
  }
  else{
   return false;
  }
 }
 public function file_txt_inf_read_full(){
  if(!$this->file_txt_read_full()) return false;
  if(!$this->data_trim("i")) return false;
  return $this->file_txt_inf("i");
 }
 public function file_txt_inf_write_full(){
  if(!$this->file_txt_inf("o")) return false;
  if(!$this->data_trim("o")) return false;
  return $this->file_txt_write_full();
 }
 protected function file_txt_inf($io = ""){
  if($io == "i"){
   $this->src = $this->data;
   $this->data = array();
   $this->comments = array();
   if(is_array($this->src)){
    $tile = "";
    $cnt = 0;
    $key = "";
    $dat = "";
    $fFile = true;
    $com = array();
    $ctxt = "";
    $this->comments = array();
    foreach($this->src as $v){
     if(strlen($v) <= 0){
      break;
     }
     if(substr($v, 0, 1) == "["){
      $fFile = false;
      break;
     }
    }
    foreach($this->src as $v){
     if(strlen($v) <= 0){
      $fFile = false;
      $com = array();
      continue;
     }
     if((substr($v, 0, 1) == ";") && ($fFile == true)){
      $ctxt = trim(substr($v, 1));
      if(strlen($ctxt) > 0){
       array_push($this->comments, $ctxt);
      }
      $ctxt = "";
      continue;
     }
     if((substr($v, 0, 1) == ";") && ($fFile == false)){
      $ctxt = trim(substr($v, 1));
      if(strlen($ctxt) > 0){
       array_push($com, $ctxt);
      }
      $ctxt = "";
      continue;
     }
     if(substr($v, 0, 1) == "["){
      $tile = trim(substr($v, 1, strpos($v, "]") - 1));
      $this->data[$tile] = array();
      $key = "";
      $cnt = 0;
      $this->comments[$tile] = array();
      if(count($com) > 0){
       $this->comments[$tile] = $com;
       $com = array();
      }
      if(strrpos($v, ";") > 0){
       $ctxt = trim(substr($v, strrpos($v, ";") + 1));
       if(strlen($ctxt) > 0){
        array_push($this->comments[$tile], $ctxt);
       }
       $ctxt = "";
      }
     }
     elseif($tile != ""){
      if(strpos($v, "=") > 0){
       $key = trim(substr($v, 0, strpos($v, "=")));
       if(is_numeric($key)) $key = "_".$key;
       $dat = trim(substr($v, strpos($v, "=") + 1));
       if(strrpos($dat, ";") > 0){
        $ctxt = trim(substr($dat, strrpos($dat, ";") + 1));
        $dat = trim(substr($dat, 0, strrpos($dat, ";")));
       }
       $this->data[$tile][$key] = $dat;
       $this->comments[$tile][$key] = array();
       if(count($com) > 0){
        $this->comments[$tile][$key] = $com;
        $com = array();
       }
       if(strlen($ctxt) > 0){
        array_push($this->comments[$tile][$key], $ctxt);
        $ctxt = "";
       }
      }
      else{
       $dat = trim($v);
       if(strrpos($dat, ";") > 0){
        $ctxt = trim(substr($dat, strrpos($dat, ";") + 1));
        $dat = trim(substr($dat, 0, strrpos($dat, ";")));
       }
       $this->data[$tile][$cnt] = $dat;
       $this->comments[$tile]["_".$cnt] = array();
       if(count($com) > 0){
        $this->comments[$tile]["_".$cnt] = $com;
        $com = array();
       }
       if(strlen($ctxt) > 0){
        array_push($this->comments[$tile]["_".$cnt], $ctxt);
        $ctxt = "";
       }
       $cnt++;
      }
     }
    }
   }
   return true;
  }
  elseif($io == "o"){
   $test = true;
   $data = array();
   if(!is_array($this->data)){
    $test = false;
   }
   else{
    foreach($this->comments as $v){
     if(is_string($v)){
      array_push($data, "; ".$v);
     }
    }
    if(count($data) > 0){
     array_push($data, "");
    }
    foreach($this->data as $k1 => $v1){
     $cnt = 0;
     if(is_array($this->comments[$k1])){
      foreach($this->comments[$k1] as $cv1){
       if(is_string($cv1)){
        $cnt++;
       }
      }
     }
     if($cnt >= 2){
      foreach($this->comments[$k1] as $cv1){
       if(is_string($cv1)){
        array_push($data, "; ".$cv1);
       }
      }
      array_push($data, "[".$k1."]");
     }
     elseif($cnt == 1){
      array_push($data, "[".$k1."] ; ".array_shift($this->comments[$k1]));
     }
     else{
      array_push($data, "[".$k1."]");
     }
     if(!is_array($v1)){
      $test = false;
      break;
     }
     else{
      $lK = 0;
      $lC = 0;
      $oK = 0;
      $oC = 0;
      foreach($v1 as $k2 => $v2){
       $k2 = (string) $k2;
       $v2 = (string) $v2;
       if($oK < strlen($k2)){
        $oK = strlen($k2);
       }
       if($oC < strlen($v2)){
        $oC = strlen($v2);
       }
      }
      foreach($v1 as $k2 => $v2){
       $v2 = (string) $v2;
       if(!is_string($v2)){
        $test = false;
        break;
       }
       if(substr($k2, 0, 1) == "_"){
        $cnt = 0;
        if(is_array($this->comments[$k1][$k2])){
         foreach($this->comments[$k1][$k2] as $cv2){
          if(is_string($cv2)){
           $cnt++;
          }
         }
        }
        $k2 = substr($k2, 1);
        $lK = $oK - strlen($k2);
        $lK--;
        $lC = $oC - strlen($v2);
        if($cnt >= 2){
         array_push($data, "");
         foreach($this->comments[$k1]["_".$k2] as $cv2){
          if(is_string($cv2)){
           array_push($data, "; ". $cv2);
          }
         }
         array_push($data, $k2.str_repeat(" ", $lK)." = ".$v2);
        }
        elseif($cnt == 1){
         array_push($data, $k2.str_repeat(" ", $lK)." = ".$v2.str_repeat(" ", $lC)." ; ".array_shift($this->comments[$k1]["_".$k2]));
        }
        else{
         array_push($data, $k2.str_repeat(" ", $lK)." = ".$v2);
        }
       }
       elseif(is_string($k2)){
        $cnt = 0;
        if(is_array($this->comments[$k1][$k2])){
         foreach($this->comments[$k1][$k2] as $cv2){
          if(is_string($cv2)){
           $cnt++;
          }
         }
        }
        $lK = $oK - strlen($k2);
        $lC = $oC - strlen($v2);
        if($cnt >= 2){
         array_push($data, "");
         foreach($this->comments[$k1][$k2] as $cv2){
          if(is_string($cv2)){
           array_push($data, "; ". $cv2);
          }
         }
         array_push($data, $k2.str_repeat(" ", $lK)." = ".$v2);
        }
        elseif($cnt == 1){
         array_push($data, $k2.str_repeat(" ", $lK)." = ".$v2.str_repeat(" ", $lC)." ; ".array_shift($this->comments[$k1][$k2]));
        }
        else{
         array_push($data, $k2.str_repeat(" ", $lK)." = ".$v2);
        }
       }
       else{
        $cnt = 0;
        if(is_array($this->comments[$k1]["_".$k2])){
         foreach($this->comments[$k1]["_".$k2] as $cv2){
          if(is_string($cv2)){
           $cnt++;
          }
         }
        }
        $lC = $oC - strlen($v2);
        if($cnt >= 2){
         array_push($data, "");
         foreach($this->comments[$k1]["_".$k2] as $cv2){
          if(is_string($cv2)){
           array_push($data, "; ". $cv2);
          }
         }
         array_push($data, $v2);
        }
        elseif($cnt == 1){
         array_push($data, $v2.str_repeat(" ", $lC)." ; ".array_shift($this->comments[$k1]["_".$k2]));
        }
        else{
         array_push($data, $v2);
        }
       }
      }
      if($test == false) break;
     }
     array_push($data, "");
    }
   }
   if(!$test){
    $this->data = $this->src;
    return false;
   }
   $this->data = $data;
   $this->src = "";
   $this->comments = "";
   return true;
  }
  else{
   return false;
  }
 }
 public function file_txt_inferw_read_full(){
  if(!$this->file_txt_read_full()) return false;
  if(!$this->data_trim("i")) return false;
  if(!$this->file_txt_inf("i")) return false;
  return $this->file_txt_inferw("i");
 }
 public function file_txt_inferw_write_full(){
  if(!$this->file_txt_inferw("o")) return false;
  if(!$this->file_txt_inf("o")) return false;
  if(!$this->data_trim("o")) return false;
  return $this->file_txt_write_full();
 }
 protected function file_txt_inferw($io = ""){
  if(!is_string($this->sign)) return false;
  if($io == "i"){
   if(!is_array($this->data) || (count($this->data) <= 0)){
    return false;
   }
   else{
    $data = $this->data;
    $this->data = "";
    foreach($data as $k1 => $v1){
     if(!is_array($v1) || (count($v1) <= 0)){
      return false;
     }
     else{
      foreach($v1 as $k2 => $v2){
       if(strpos($v2, $this->sign)){
        $this->data = $v2;
        if(!$this->file_txt_csv("i")){
         $this->data = $data;
         return false;
        }
        $data[$k1][$k2] = $this->data;
        $this->data = "";
       }
      }
     }
    }
    $this->data = $data;
    return true;
   }
  }
  elseif($io == "o"){
   if(!is_array($this->data) || (count($this->data) <= 0)){
    return false;
   }
   else{
    $data = $this->data;
    $this->data = "";
    foreach($data as $k1 => $v1){
     if(!is_array($v1) || (count($v1) <= 0)){
      return false;
     }
     else{
      foreach($v1 as $k2 => $v2){
       if(is_array($v2)){
        $this->data = $v2;
        if(!$this->file_txt_csv("o")){
         $this->data = $data;
         return false;
        }
        $data[$k1][$k2] = $this->data;
        $this->data = "";
       }
      }
     }
    }
    $this->data = $data;
    return true;
   }
  }
  else{
   return false;
  }
 }
 public function file_txt_serial_read_full(){
  if(!$this->file_txt_read_full()) return false;
  return $this->file_txt_serial("i");
 }
 public function file_txt_serial_write_full(){
  if(!$this->file_txt_serial("o")) return false;
  return $this->file_txt_write_full();
 }
 protected function file_txt_serial($io = ""){
  if($io == "i"){
   if(!is_string($this->data)){
    return false;
   }
   else{
    $this->data = unserialize($this->data);
   }
   return true;
  }
  elseif($io == "o"){
   $this->data = serialize($this->data);
   return true;
  }
  else{
   return false;
  }
 }
 public function file_bin_read_sign($sign = true, $skip = 0){
  if(!(is_bool($sign)||is_int($sign))||!is_int($skip)) return false;
  if(($this->path == "")||($this->file == "")) return false;
  $p = $this->vali_path($this->path);
  $f = $this->vali_path($this->path."/".$this->file);
  if(!$this->perms($p, "dr")) return false;
  if(!$this->perms($f, "fr")) return false;
  $data = "";
  $r = true;
  $fp = fopen($f, "rb");
  if($fp == false) $r = false;
  if($r == true){
   while(!feof($fp)){
    if($skip <= 0){
     if($sign == true || $sign > 0){
      if(is_bool($sign)) $sign = filesize($f) - $skip;
      $data = fread($fp, $sign);
      break;
     }
     else{
      break;
     }
    }
    else{
     fgetc($fp);
     $skip--;
    }
   }
  }
  fclose($fp);
  if($r == true){
   $this->data = $data;
  }
  return $r;
 }
 public function file_bin_read_full(){
  if(($this->path == "")||($this->file == "")) return false;
  $p = $this->vali_path($this->path);
  $f = $this->vali_path($this->path."/".$this->file);
  if(!$this->perms($p, "dr")) return false;
  if(!$this->perms($f, "fr")) return false;
  $data = "";
  $r = true;
  $fp = fopen($f, "rb");
  if($fp == false) $r = false;
  if($r == true){
   $data = fread($fp, filesize($f));
  }
  fclose($fp);
  if($r == true){
   $this->data = $data;
  }
  return $r;
 }
 public function file_bin_write_sign($sign = 0, $skip = 0){
  if(!(is_bool($sign)||is_int($sign))||!is_int($skip)) return false;
  if(($this->path == "")||($this->file == "")) return false;
  $p = $this->vali_path($this->path);
  $f = $this->vali_path($this->path."/".$this->file);
  if(!$this->perms($p, "drw")) return false;
  if($this->perms($f, "-")){
   if(!$this->perms($f, "frw")) return false;
  }
  if(gettype($this->data) != "string") return false;
  $r = true;
  if($this->perms($f, "f")){
   $fp = fopen($f, "rb+");
  }
  else{
   $fp = fopen($f, "wb");
  }
  if($fp == false) return false;
  if($skip > 0){
   while($skip > 0 && !feof($fp)){
    fgetc($fp);
    $skip--;
   }
  }
  if($fp == false) return false;
  if($sign == true){
   $sign = strlen($this->data);
  }
  elseif(is_int($sign)){
   $sign = $sign;
  }
  else{
   $sign = 0;
  }
  if(fwrite($fp, $this->data, $sign) == false) $r = false;
  fclose($fp);
  return $r;
 }
 public function file_bin_write_full(){
  if(($this->path == "")||($this->file == "")) return false;
  $p = $this->vali_path($this->path);
  $f = $this->vali_path($this->path."/".$this->file);
  if(!$this->perms($p, "drw")) return false;
  if($this->perms($f, "-")){
   if(!$this->perms($f, "frw")) return false;
  }
  if(gettype($this->data) != "string") return false;
  $r = true;
  $fp = fopen($f, "wb");
  if($fp == false) return false;
  if(fwrite($fp, $this->data, strlen($this->data)) == false) $r = false;
  fclose($fp);
  return $r;
 }
 public function file_bin_erase(){
  return $this->file_erase();
 }
 public function file_bin_copy($new = ""){
  return $this->file_copy($new);
 }
 public function file_bin_move($new = ""){
  return $this->file_move($new);
 }
 public function file_bin_rename($new = ""){
  return $this->file_rename($new);
 }
 public function file_bin_info(){
  return $this->file_info();
 }
 protected function file_erase(){
  if(($this->path == "")||($this->file == "")) return false;
  $p = $this->vali_path($this->path);
  $f = $this->vali_path($this->path."/".$this->file);
  if(!$this->perms($p, "drw")) return false;
  if(!$this->perms($f, "frw")) return false;
  return @unlink($f);
 }
 protected function file_copy($new){
  if(!is_string($new)) return false;
  if(($this->path == "")||($this->file == "")||$new == "") return false;
  $new = $this->vali_path($new);
  $fo = $this->vali_path($this->path."/".$this->file);
  $fn = $this->vali_path($new."/".$this->file);
  if(!$this->perms($this->path, "dr")) return false;
  if(!$this->perms($fo, "fr")) return false;
  if(!$this->perms($new, "drw")) return false;
  if($this->perms($fn, "-")) return false;
  return @copy($fo, $fn);
 }
 protected function file_move($new){
  if(!$this->file_copy($new)) return false;
  if(!$this->file_erase()) return false;
  return true;
 }
 protected function file_rename($new){
  if(!is_string($new)) return false;
  if(($this->path == "")||($this->file == "")||$new == "") return false;
  $new = $this->vali_path($new);
  $fo = $this->vali_path($this->path."/".$this->file);
  $fn = $this->vali_path($this->path."/".$new);
  if(!$this->perms($this->path, "drw")) return false;
  if(!$this->perms($fo, "frw")) return false;
  if($this->perms($fn, "-")) return false;
  return @rename($fo, $fn);
 }
 protected function file_info(){
  if(($this->path == "")||($this->file == "")) return false;
  $p = $this->vali_path($this->path);
  $f = $this->vali_path($this->path."/".$this->file);
  if(!$this->perms($p, "dr")) return false;
  if(!$this->perms($f, "fr")) return false;
  $data = array();
  $fp = fopen($f, "r");
  $t = fstat($fp);
  fclose($fp);
  $data["full"] = substr($f, strrpos($f, "/") + 1);
  if(strpos($data["full"], ".") > 0){
   $data["name"] = substr($data["full"], 0, strrpos($data["full"], "."));
   $data["ext"] = substr($data["full"], strrpos($data["full"], ".") + 1);
  }
  else{
   $data["name"] = $data["full"];
   $data["ext"] = "";
  }
  $data["path"] = $p;
  $data["folder"] = substr($p, strrpos($p, "/") + 1);
  $data["type"] = "file";
  $data["right"] = (is_readable($f) == true) ? "r" : "-";
  $data["right"] .= (is_writeable($f) == true) ? "w" : "-";
  $data["right"] .= (is_executable($f) == true) ? "x" : "-";
  $data["target"] = "";
  $data["mime"] = mime_content_type($f);
  $data = array_merge($data, $t);
  $t = array();
  foreach($data as $k => $v){
   if(is_string($k)) $t[$k] = $v;
  }
  $data = $t;
  $this->info = $data;
  return true;
 }
 public function link_read(){
  if(($this->path == "")||($this->file == "")) return false;
  $p = $this->vali_path($this->path);
  $f = $this->vali_path($this->path."/".$this->file);
  if(!$this->perms($p, "dr")) return false;
  if(!$this->perms($f, "lr")) return false;
  $data = readlink($f);
  if($data == false) return false;
  if(substr($data, 0, 1) == "/"){
   $data = realpath($data);
  }
  else{
   $data = realpath($p."/".$data);
  }
  if($this->perms($data, "f")){
   $this->path = substr($data, 0, strrpos($data, "/"));
   $this->file = substr($data, strrpos($data, "/") + 1);
   $this->folder = basename($this->path);
  }
  elseif($this->perms($data, "d")){
   $this->path = $data;
   $this->file = "";
   $this->folder = basename($this->path);
  }
  return true;
 }
 public function link_write(){
  if(!is_string($this->data)) return false;
  if(($this->path == "")||($this->file == "")) return false;
  $this->data = $this->vali_path($this->data);
  $p = $this->vali_path($this->path);
  $f = $this->vali_path($this->path."/".$this->file);
  if(!$this->perms($p, "drw")) return false;
  if($this->perms($f, "-")) return false;
  if(!symlink($this->data, $f)) return false;
  return true;
 }
 public function link_erase(){
  if(($this->path == "")||($this->file == "")) return false;
  $p = $this->vali_path($this->path);
  $f = $this->vali_path($this->path."/".$this->file);
  if(!$this->perms($p, "drw")) return false;
  if(!$this->perms($f, "lrw")) return false;
  return @unlink($f);
 }
 public function link_copy($new = ""){
  if(!is_string($new)) return false;
  if(($this->path == "")||($this->file == "")||$new == "") return false;
  $data = $new;
  $new = $this->vali_path($new);
  $fo = $this->vali_path($this->path."/".$this->file);
  $fn = $this->vali_path($new."/".$this->file);
  $p = $this->path;
  $f = $this->file;
  if(!$this->perms($this->path, "dr")) return false;
  if(!$this->perms($fo, "lr")) return false;
  if(!$this->perms($new, "drw")) return false;
  if($this->perms($fn, "-")) return false;
  if(!$this->link_read()) return false;
  $this->data = $this->path."/".$this->file;
  $this->file = $f;
  $this->path = $data;
  if(!$this->link_write()){
   $this->file = $f;
   $this->path = $p;
   return false;
  }
  $this->file = $f;
  $this->path = $p;
  return true;
 }
 public function link_move($new = ""){
  if(!is_string($new)) return false;
  if(($this->path == "")||($this->file == "")||$new == "") return false;
  $new = $this->vali_path($new);
  $fo = $this->vali_path($this->path."/".$this->file);
  $fn = $this->vali_path($new."/".$this->file);
  if(!$this->perms($this->path, "drw")) return false;
  if(!$this->perms($fo, "lrw")) return false;
  if(!$this->perms($new, "drw")) return false;
  if($this->perms($fn, "-")) return false;
  if(!$this->link_copy($new)) return false;
  if(!$this->link_erase()) return false;
  return true;
 }
 public function link_rename($new = ""){
  if(!is_string($new)) return false;
  if(($this->path == "")||($this->file == "")||$new == "") return false;
  $fo = $this->vali_path($this->path."/".$this->file);
  $fn = $this->vali_path($this->path."/".$new);
  $p = $this->path;
  $f = $this->file;
  if(!$this->perms($this->path, "drw")) return false;
  if(!$this->perms($fo, "lrw")) return false;
  if($this->perms($fn, "-")) return false;
  $this->link_read();
  $this->data = $this->path."/".$this->file;
  $this->file = substr($fn, strrpos($fn, "/") + 1);
  $this->path = $p;
  $this->link_write();
  $this->file = $f;
  $this->link_erase();
  return true;
 }
 public function link_info(){
  if(($this->path == "")||($this->file == "")) return false;
  $tp = $this->path;
  $tf = $this->file;
  $p = $this->vali_path($this->path);
  $f = $this->vali_path($this->path."/".$this->file);
  if(!$this->perms($p, "dr")) return false;
  if(!$this->perms($f, "lr")) return false;
  $data = array();
  $this->link_read();
  $target = $this->path."/".$this->file;
  $this->path = $tp;
  $this->file = $tf;
  $fp = fopen($f, "r");
  $t = fstat($fp);
  fclose($fp);
  $data["full"] = substr($f, strrpos($f, "/") + 1);
  if(strpos($data["full"], ".") > 0){
   $data["name"] = substr($data["full"], 0, strrpos($data["full"], "."));
   $data["ext"] = substr($data["full"], strrpos($data["full"], ".") + 1);
  }
  else{
   $data["name"] = $data["full"];
   $data["ext"] = "";
  }
  $data["path"] = $p;
  $data["folder"] = substr($p, strrpos($p, "/") + 1);
  $data["type"] = "link";
  $data["right"] = (is_readable($f) == true) ? "r" : "-";
  $data["right"] .= (is_writeable($f) == true) ? "w" : "-";
  $data["right"] .= (is_executable($f) == true) ? "x" : "-";
  $data["target"] = $target;
  $data["mime"] = mime_content_type($f);
  $data = array_merge($data, $t);
  $t = array();
  foreach($data as $k => $v){
   if(is_string($k)) $t[$k] = $v;
  }
  $data = $t;
  $this->info = $data;
  return true;
 }
 
 /**
  * Funktion zum Lesen eines Ordners
  * 
  * @param mögliche Spaltennamen<br>
  *      full, name, ext, path, folder, type, right, target, mime, dev, ino, mode, nlink, uid, gid, rdev, size, atime, mtime, ctime, blksize, blocks
  * 
  * @param string $sort
  *      Sortiere (bsp:name,0;type,1)<br>
  *      name,0 Sortiere Spalte name aufsteigend<br>
  *      name,1 Sortiere Spalte name absteigend<br>
  *      ; weitere Sortierungen
  * @param string $filter
  *      Filtern (bsp.: aa;name:test1,test2:;ext:inf:;size::>300)<br>
  *      bsp.:Modus;Spaltenname:Textvergleich:Zahlenvergleich;Spaltenname:Textvergleich:Zahlenvergleich
  *      Modus (aa, ao, oa, oo)<br>
  *      1. Buchstabe sind die Spalten zueinnander<br>
  *      2. und weitere Buchstabe sind die Zeilen in einer Spalte<br>
  *      a f&uuml;r UND-Verknüpfung<br>
  *      o f&uuml;r ODER-Verknüpfung<br>
  *      Vergleichoperatoren (optional)<br>
  *      > f&uuml;r Gr&ouml;&szlig;er<br>
  *      < f&uuml;r Kleiner<br>
  *      = f&uuml;r Gleich<br>
  *      b f&uuml;r Gr&ouml;&szlig;er oder Gleich<br>
  *      l f&uuml;r Kleiner oder Gleich
  * @param string $output<br>
  *      Ausgabe begrenzen<br>
  *      Spaltenamen (bsp.:full,type,size)
  * @return boolean
  */
 public function directory_read($sort = "", $filter = "", $output = "*"){
  if(!is_string($sort)||!is_string($filter)||!is_string($output)) return false;
  if($this->path == "") return false;
  $p = $this->vali_path($this->path);
  if(!$this->perms($p, "dr")) return false;
  $dp = opendir($p);
  $l = array();
  while($i = readdir($dp)){
   if(($i != ".")&&($i != "..")){
    $l[$i] = array();
   }
   else{
    continue;
   }
   if($this->perms($p."/".$i, "d")){
    $this->setFile($i);
    if($this->directory_info()){
     $l[$i] = $this->getInfo();
    }
    $this->setFile("");
   }
   elseif($this->perms($p."/".$i, "l")){
    $this->setFile($i);
    if($this->link_info()){
     $l[$i] = $this->getInfo();
    }
    $this->setFile("");
   }
   elseif($this->perms($p."/".$i, "f")){
    $this->setFile($i);
    if($this->file_info()){
     $l[$i] = $this->getInfo();
    }
    $this->setFile("");
   }
  }
  closedir($dp);
  if($filter != ""){
   $l = $this->data_filter($l, $filter);
  }
  if($sort != ""){
   $l = $this->data_sort($l, $sort);
  }
  if($output != ""){
   if($output != "*"){
    $l = $this->data_limit($l, $output);
   }
  }
  else{
   $l = array();
  }
  $this->data = $l;
  return true;
 }
 public function directory_write($mode = "0777"){
  if(!is_string($mode)||!is_numeric($mode)) return false;
  if(($this->path == "")||($mode == "")) return false;
  $p = $this->vali_path($this->path);
  $f = $this->vali_path($this->path."/".$this->data);
  if(!$this->perms($p, "drw")) return false;
  if($this->perms($f, "-")) return false;
  return mkdir($f, $mode);
 }
 public function directory_erase(){
  if($this->path == "") return false;
  $p = $this->vali_path($this->path);
  $f = $this->vali_path($this->path."/".$this->data);
  if(!$this->perms($p, "drw")) return false;
  if(!$this->perms($f, "drw")) return false;
  $pt = $this->path;
  $dt = $this->data;
  $this->path = $f;
  $this->directory_read();
  $l = $this->data;
  $this->path = $pt;
  $this->data = $dt;
  foreach($l as $k => $v){
   if(($k == ".")||($k == "..")){
    $v = $v;
    unset($l[$k]);
   }
  }
  $l = count($l);
  if($l > 0) return false;
  return rmdir($f);
 }
 public function directory_copy($new = ""){
  
 }
 public function directory_move($new = ""){
  
 }
 public function directory_rename($new = ""){
  
 }
 public function directory_index($offset = ""){
  if($this->path == "") return false;
  $p = $this->vali_path($this->path);
  if(!$this->perms($p, "dr")) return false;
  $l = array();
  if($offset != ""){
   $offset .= "/";
  }
  $pt = $this->path;
  $dt = $this->data;
  $this->directory_read("full,0", "o;type:directory", "full");
  $d = $this->data;
  $this->path = $pt;
  $this->data = $dt;
  if(count($d) > 0){foreach($d as $v){
   array_push($l, $offset.$v["full"]);
   $pt = $this->path;
   $dt = $this->data;
   $this->path = $p."/".$v["full"];
   $this->directory_index($offset.$v["full"]);
   $di = $this->data;
   $this->path = $pt;
   $this->data = $dt;
   $l = array_merge($l, $di);
  }}
  $this->data = $l;
  return true;
 }
 public function directory_info(){
  if(($this->path == "")||($this->file == "")) return false;
  $p = $this->vali_path($this->path);
  $f = $this->vali_path($this->path."/".$this->file);
  if(!$this->perms($p, "dr")) return false;
  if(!$this->perms($f, "dr")) return false;
  $data = array();
  $t = stat($f);
  $data["full"] = substr($f, strrpos($f, "/") + 1);
  if(strpos($data["full"], ".") > 0){
   $data["name"] = substr($data["full"], 0, strrpos($data["full"], "."));
   $data["ext"] = substr($data["full"], strrpos($data["full"], ".") + 1);
  }
  else{
   $data["name"] = $data["full"];
   $data["ext"] = "";
  }
  $data["path"] = $p;
  $data["folder"] = substr($p, strrpos($p, "/") + 1);
  $data["type"] = "directory";
  $data["right"] = (is_readable($f) == true) ? "r" : "-";
  $data["right"] .= (is_writeable($f) == true) ? "w" : "-";
  $data["right"] .= (is_executable($f) == true) ? "x" : "-";
  $data["target"] = "";
  $data["mime"] = mime_content_type($f);
  $data = array_merge($data, $t);
  $t = array();
  foreach($data as $k => $v){
   if(is_string($k)) $t[$k] = $v;
  }
  $data = $t;
  $this->info = $data;
  return true;
 }
 private function perms($io, $perms){
  $perms = str_split($perms, 1);
  $test = array();
  if(count($perms)<= 0) return false;
  switch($perms[0]){
   default:
    array_push($test, false);
    break;
   case "-":
    array_push($test, file_exists($io));
    break;
   case "f":
    array_push($test, is_file($io));
    break;
   case "d":
    array_push($test, is_dir($io));
    break;
   case "l":
    array_push($test, is_link($io));
    break;
  }
  array_shift($perms);
  if(count($perms) > 0){
   foreach($perms as $v){
    switch($v){
     default:
      array_push($test, false);
      break;
     case "r":
      array_push($test, is_readable($io));
      break;
     case "w":
      array_push($test, is_writable($io));
      break;
     case "x":
      array_push($test, is_executable($io));
      break;
    }
   }
  }
  foreach($test as $v){
   if($v == false) return false;
  }
  return true;
 }
 private function vali_path($io){
  $rp = realpath(".");
  if(substr($io, 0, strlen($rp)) != $rp){
   $io = $rp."/".$io;
  }
  while(strpos($io, "./") != false){
   $io = substr($io, 0, strpos($io, "./") - 1).substr($io, (strpos($io, "./") + 2));
  }
  while(strpos($io, "../") != false){
   $io = substr($io, 0, strpos($io, "../") - 1).substr($io, (strpos($io, "../") + 3));
  }
  if(substr($io, 0, strlen($rp)) != $rp){
   $io = $rp;
  }
  return $io;
 }
 public function data_trim($io = ""){
  if($io == "i"){
   switch(gettype($this->data)){
   default:
    break;
   case "string":
    $this->data = trim($this->data);
    break;
   case "array":
    $data = array();
    foreach($this->data as $k => $v){
     $data[$k] = trim($v);
    }
    $this->data = $data;
    break;
   }
  }
  elseif($io == "o"){
   switch(gettype($this->data)){
   default:
    break;
   case "string":
    $this->data = $this->data."\n";
    break;
   case "array":
    $data = array();
    $i = 0;
    $l = "";
    foreach($this->data as $k => $v){
     if($i > 0){
      $data[$l] .= "\n";
     }
     $data[$k] = $v;
     $l = $k;
     $i++;
    }
    $this->data = $data;
    break;
   }
  }
  else{
   return false;
  }
  return true;
 }
 protected function data_sort($list, $sort){
  $data = $this->data;
  $sign = $this->sign;
  $this->data = $sort;
  $this->sign = ";";
  $this->file_txt_csv("i");
  $this->sign = ",";
  $this->file_txt_csv("i");
  $sort = $this->data;
  $this->data = $data;
  $this->sign = $sign;
  if(count($sort) > 0){foreach($sort as $v){
   $ls = array();
   foreach($list as $lv){
    array_push($ls, $lv[$v[0]]);
   }
   if($v[1] == "0"){
    sort($ls, $this->st[$v[0]]);
   }
   else{
    rsort($ls, $this->st[$v[0]]);
   }
   $nl = array();
   while(count($ls) > 0){
    $tv = array_shift($ls);
    foreach($list as $kn => $vn){
     if($vn[$v[0]] == $tv){
      $nl[$kn] = $vn;
      unset($list[$kn]);
      break;
     }
    }
   }
   $list = $nl;
  }}
  return $list;
 }
 
 protected function data_filter($list, $filter){
  $data = $this->data;
  $sign = $this->sign;
  $this->data = $filter;
  $this->sign = ";";
  $this->file_txt_csv("i");
  $this->sign = ":";
  $this->file_txt_csv("i");
  $filter = $this->data;
  $mode = array_shift($filter);
  $mode = str_split($mode[0], 1);
  foreach($filter as $k1 => $v1){
   $this->data = $v1[1];
   $this->sign = ",";
   $this->file_txt_csv("i");
   $filter[$k1][1] = $this->data;
   $this->data = $v1[2];
   $this->sign = ",";
   $this->file_txt_csv("i");
   $filter[$k1][2] = $this->data;
  }
  $this->sign = $sign;
  $this->data = $data;
  $mode = array(array_shift($mode), $mode);
  for($i = 0; $i < count($filter); $i++){
   if(!is_string($mode[1][$i])){
    $mode[1][$i] = "o";
   }
   if($filter[$i][1][0] == ""){
    $filter[$i][1] = array();
   }
   if($filter[$i][2][0] == ""){
    $filter[$i][2] = array();
   }
  }
  $nl = array();
  $this->comments = array(); 
  foreach($list as $k1 => $v1){
   $mt = 0;
   for($i = 0; $i < count($mode[1]); $i++){
    $mc = 0;
    $mm = count($filter[$i][1]) + count($filter[$i][2]);
    if(count($filter[$i][1]) > 0){
     foreach($filter[$i][1] as $v2){
      if($v2 == $v1[$filter[$i][0]]){
       $mc++;
      }
     }
    }
    if(count($filter[$i][2]) > 0){
     foreach($filter[$i][2] as $v2){
      $vt = $v1[$filter[$i][0]];
      if(is_numeric($vt)){
       $vt = (float) $vt;
      }
      else{
       $vi = 0;
       $vt = array_reverse(str_split($vt, 1));
       for($i = 0; $i < count($vt); $i++){
        $vi += ord($vt[$i]) * pow(256, $i);
       }
       $vt = $vi;
      }
      $v2 = array(substr($v2, 0, 1), (float) substr($v2, 1));
      switch($v2[0]){
       default:
       case "=":
        if($vt == $v2[1]){
         $mc++;
        }
        break;
       case ">":
        if($vt > $v2[1]){
         $mc++;
        }
        break;
       case "<":
        if($vt < $v2[1]){
         $mc++;
        }
        break;
       case "b":
        if($vt >= $v2[1]){
         $mc++;
        }
        break;
       case "l":
        if($vt <= $v2[1]){
         $mc++;
        }
        break;
      }
     }
    }
    if(!is_array($this->comments[$k1])){
     $this->comments[$k1] = array(0, 0);
    }
    $this->comments[$k1][0] += $mc;
    $this->comments[$k1][1] += $mm;
    if($mode[1][$i] == "a"){
     if($mc == $mm){
      $mt++;
     }
    }
    else{
     if($mc > 0){
      $mt++;
     }
    }
   }
   if($mode[0] == "a"){
    if($mt == count($mode[1])){
     $nl[$k1] = $v1;
    }
   }
   else{
    if($mt > 0){
     $nl[$k1] = $v1;
    }
   }
  }

  $list = $nl;
  return $list;
 }
 
 protected function data_limit($list, $output){
  $data = $this->data;
  $sign = $this->sign;
  $this->data = $output;
  $this->sign = ",";
  $this->file_txt_csv("i");
  $output = $this->data;
  $this->sign = $sign;
  $this->data = $data;
  $nl = array();
  if(count($list) > 0){foreach($list as $k1 => $v1){
   foreach($v1 as $k2 => $v2){
    foreach($output as $v3){
     if($v3 == $k2){
      if(!is_array($nl[$k1])){
       $nl[$k1] = array();
      }
      $nl[$k1][$k2] = $v2;
     }
    }
   }
  }}
  $list = $nl;
  return $list;
 }
}
?>