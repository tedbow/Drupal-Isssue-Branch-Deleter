<?php


namespace GitDeleter;



class BranchDeleter {

  protected const ISSUE_STATUS_CLOSED_WONT_FIX = "5";
  protected const ISSUE_STATUS_CLOSED_WORKS_AS_DESIGNED = "6";
  protected const ISSUE_STATUS_CLOSED_FIXED = "7";

  public function shellExecSplit($string) {
    $output = shell_exec($string);
    $output = preg_split('/\n+/', trim($output));
    $output = array_map(function ($line) {
      return trim($line);
    }, $output);

    return array_filter($output);

  }


  const DIR = '/Users/ted.bowman/sites/d8';
  public function deleteBranches() {
    chdir(static::DIR);
    $branches = $this->shellExecSplit('git branch --l');
    foreach ($branches as $branch) {
      $parts = explode('-', $branch);
      if (!is_numeric($parts[0])) {
        continue;
      }
      $nid = $parts[0];
      $branch_cnt = 0;
      if ($issue = Request::getSingle("https://www.drupal.org/api-d7/node.json?nid=$nid&type=project_issue")) {
        if (in_array($issue->field_issue_status , [
          static::ISSUE_STATUS_CLOSED_FIXED,
          //static::ISSUE_STATUS_CLOSED_WONT_FIX,
          static::ISSUE_STATUS_CLOSED_WORKS_AS_DESIGNED,
        ])) {
          $this->shellExecSplit("git branch -D $branch");
          print "Deleted $branch: " . $issue->title . "\n";
          $branch_cnt++;
        }
        else {
          $a = "d";
        }
      }
    }
    print "deleted: $branch_cnt";

  }

}