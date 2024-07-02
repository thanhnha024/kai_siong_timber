<?php
// Get all file in sub golder 
foreach (glob(THEME_DIR . '-child' . "/includes/*/*.php") as $file_name_sub) {
  if (!isset($file_name_sub)) return;
  require_once($file_name_sub);
}
