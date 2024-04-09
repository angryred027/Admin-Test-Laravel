#!/bin/bash

# {string} message
function showMessage() {
  echo '------------------------------------------------------'
  echo $1
}

# {unknown} parameter
function isParamterNotEmpty() {
if [ "$1" == '' ]; then
  showMessage 'Invalid Parameter. \nNo parameter.'
  exit
fi
}

# {string} parameter
# {string} target
function isContainTargetString() {
if [[ "$1" != *$2* ]]; then
  showMessage "Invalid Parameter. \nNo contains '$2'."
  exit
fi
}

# @param {string} value of array
# @return {string} value
function getArrayElement() {
  local TMP="$1"
  if [ "$TMP" == '[' ]; then
    # 実行結果は空文字列
    continue
  elif [ "$TMP" == ']' ]; then
    continue
  elif [ "$TMP" == '[]' ]; then
    continue
  else
    # "を削除
    local TMP_ID="${TMP//\"/}"
    # ,を削除
    TMP_ID="${TMP_ID//,/}"
    echo $TMP_ID
  fi
}

