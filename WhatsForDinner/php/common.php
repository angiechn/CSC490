<?php

/**
  * Escapes HTML for output
  *
  */

  function escape($html) {
    if (is_array($html)) {
      return array_map('escape', $html);
    }
    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
  }
  