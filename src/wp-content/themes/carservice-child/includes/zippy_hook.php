<?php

function is_sub_category($parent)
{
  if (is_admin()) return;

  if (isset($parent->parent) && $parent->parent > 0)
    return true;

  // return false;
}
