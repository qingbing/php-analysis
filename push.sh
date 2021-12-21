#!/bin/bash
# clear screen
clear

# fetch current branch
CUR_BRANCH=$(git branch | grep '*' | awk -F ' ' '{print $2}')

# get the release comment
commitMsg=$1
# check comment
if [[ ! -n $commitMsg ]]; then
	commitMsg=`date "+%Y${dateExp}%m${dateExp}%d${exp}%H${secExp}%M"`
fi

# commit the comment
git commit -m $commitMsg

# push
git push origin $CUR_BRANCH
# tip
if [[ $? -ne 0 ]]; then
	echo "push fail"
else
	echo "push success"
fi