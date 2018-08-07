<?php

function jobadmintablesort($query,$page,$nlimit,$url,$options){
# the total number of results
  $result = mysql_query($query);
  $nrow = mysql_num_rows($result);
# only the page
  $offset = $page*$nlimit;
  $query.="LIMIT $offset,$nlimit";
  $result = mysql_query($query);
  $tableheader = '<table class="queryTable">
    <tbody id="tbodytag">
    <tr class="queryTitle">
    <td><a href="jobadmin.php?sortcol=id&sortorder='.$options['id'].'"> Job Id</a></td>
    <td><a href="jobadmin.php?sortcol=created_by&sortorder='.$options['created_by'].'"> User Id</a></td>
    <td><a href="jobadmin.php?sortcol=pdbid&sortorder='.$options['pdbid'].'"> Protein</td>
    <td><a href="jobadmin.php?sortcol=mutation&sortorder='.$options['mutation'].'"> Mutation</td>
    <td><a href="jobadmin.php?sortcol=flex&sortorder='.$options['flex'].'"> Backbone</td>
    <td><a href="jobadmin.php?sortcol=relax&sortorder='.$options['relax'].'"> Pre-relax</td>
    <td><a href="jobadmin.php?sortcol=ddg&sortorder='.$options['ddg'].'"> &Delta;&Delta;G <small> (kcal/mol) </small></td>
    <td><a href="jobadmin.php?sortcol=status&sortorder='.$options['status'].'"> Status </td>
    <td>Action </td>
    </tr>';
  echo "$tableheader";
  $flag = -1; # counter odd/even rows
  while ( $row = mysql_fetch_array($result)){

    $jobid = $row['id'];
    $pdbid = $row['pdbid'];
    $created_by = $row['created_by'];
    $mutation = $row['mutation'];
    $flex = $row['flex'];
    $relax = $row['relax'];
    $ddg = $row['ddg'];
    $ddg = sprintf("%4.2f",$ddg);
    $status = $row['status'];
    $message = $row['message'];

    $flexstr= $flex==1?"flexible":"fixed";
    $relaxstr = $relax==1?"yes":"no";
    switch ($status) {
      case 0:
	$statusstr="Wating";break;
      case 1:
	$statusstr="Processing";break;
      case 2:
	$statusstr="Finished";break;
      case 3:
	$statusstr="Failed";break;
    }


    $haspdb = $row['!ISNULL(pdb)'];
    if ($haspdb) {
      $pdbstr = "<a href='downloadpdb.php?id=$jobid'><img src='style/img/zipped.gif' title='download output pdb' border='0px'> </a>";
      //			$url = "loadPage(\"downloadpdb.php?id=$jobid\")";
      //			$pdbstr = "<img src='style/img/zipped.gif' title='download out pdb' onclick ='$url' style='cursor:pointer;' >";
    }else{
      $pdbstr = "";
    }
    $href = "delentry($jobid)";
    $str = "<a href='javascript:;' onclick='$href'> <img src='style/img/delete.png' title='delete this entry' border='0px'> </a>";

    $bgcolorstr= "";
    if($flag == 1) {$bgcolorstr="bgcolor='#ECE9DA'";}
    echo "<tr id='entrytag$jobid' $bgcolorstr>";
    echo "<td valign=top align=left >$jobid</td>";
    echo "<td valign=top align=left >$created_by </td>";
    echo "<td valign=top align=left >$pdbid </td>";
    echo "<td valign=top align=left >$mutation </td>";
    echo "<td valign=top align=left >$flexstr </td>";
    echo "<td valign=top align=left >$relaxstr </td>";
    echo "<td valign=top align=left >$ddg </td>";
    echo "<td valign=top align=left > $statusstr </td>";
    echo "<td valign=top align=right > $pdbstr $str</td>";
    echo "</tr>";
    $flag*=-1;
  }

  echo "</tbody></table>";
  $pagelast = $page - 1;
  $pagenext = $page + 1;
  $ntotalpage = ceil($nrow/$nlimit);
  if ($ntotalpage == 0) { # for display
    $pageindex = 0;
  }
  else { 
    $pageindex = $page +1;
  }

  if ($offset > 0) {
    echo " <a href=\"$url&page=$pagelast\"> &lt;prev </a>"; 
  }
  echo "( page $pageindex/$ntotalpage )";
  if ($offset+$nlimit < $nrow) {
    echo " <a href=\"$url&page=$pagenext\"> next &gt;</a>";
  }
}


?>
