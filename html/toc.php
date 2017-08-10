<?php
include ('includes/header_inner.php');
?>

<div class="container-fluid">
<div class="row"> 
	<div class="col-sm-8 col-sm-offset-2"> 

<?php
require_once("connect.php");
require_once("common.php");

if(isset($_GET['bookid'])){$book_id = $_GET['bookid'];}else{$book_id = '';}

if(!(isValidId($book_id)))
{
	echo "Invalid URL";
	exit(1);
}

$stack = array();
$p_stack = array();
$first = 1;

$li_id = 0;
$ul_id = 0;

$plus_link = "<img class=\"bpointer\" title=\"Expand\" src=\"images/plus.gif\" alt=\"Expand or Collapse\" onclick=\"display_block_inside(this)\" />";
$bullet = "<img class=\"bpointer\" src=\"images/bullet_1.gif\" alt=\"Point\" />";

$month_name = array("0"=>"","1"=>"January","2"=>"February","3"=>"March","4"=>"April","5"=>"May","6"=>"June","7"=>"July","8"=>"August","9"=>"September","10"=>"October","11"=>"November","12"=>"December");

$query = "select btitle from books_toc where book_id='$book_id' order by slno limit 1";
$result = $db->query($query);
$row = $result->fetch_assoc();


echo "<div class=\"page_booktitle\">" . $row['btitle'] . "</div>";

$query = "select * from books_toc where book_id='$book_id' order by slno";
$result = $db->query($query);
$num_rows = $result ? $result->num_rows : 0;

if($num_rows > 0)
{
	echo "<div class=\"treeview tab-pane\">";
	for($i=1;$i<=$num_rows;$i++)
	{
		$row = $result->fetch_assoc();
		
		$level = $row['level'];
		$title = $row['title'];
		$page = $row['page'];
		$slno = $row['slno'];
		$authorname = '';
		
		$title = preg_replace('/(.*) - <i>(.*)<\/i>/', "$1", $row['title']);
		if(preg_match('/(.*) - <i>(.*)<\/i>/', $row['title']))
		{
			$authorname = preg_replace('/(.*) - <i>(.*)<\/i>/', "<i>$2</i>", $row['title']);
		}
		if($authorname != "")
		{
			$title = '<span class="sub_titlespan"><a target="_blank" href="books/' . $book_id . '.pdf#page=' . $page . '">' . $title . ' </a></span><br/><span class="authorspan">&nbsp;&nbsp;&nbsp;-&nbsp;' . $authorname . '</span>';
		}
		else
		{
			$title = '<span class="sub_titlespan"><a target="_blank" href="books/' . $book_id . '.pdf#page=' . $page . '">' . $title . ' </a></span>';
		}
		
		if($first)
		{
			array_push($stack,$level);
			$ul_id++;
			echo "<ul id=\"ul_id$ul_id\">\n";
			array_push($p_stack,$ul_id);
			$li_id++;
			$deffer = display_tabs($level) . "<li id=\"li_id$li_id\">:rep:$title";
			$first = 0;
		}
		elseif($level > $stack[sizeof($stack)-1])
		{
			$deffer = preg_replace('/:rep:/',"$plus_link",$deffer);
			echo $deffer;			

			$ul_id++;			
			$li_id++;			
			array_push($stack,$level);
			array_push($p_stack,$ul_id);
			$deffer = "\n" . display_tabs(($level-1)) . "<ul class=\"dnone\" id=\"ul_id$ul_id\">\n";
			$deffer = $deffer . display_tabs($level) ."<li id=\"li_id$li_id\">:rep:$title";
		}
		elseif($level < $stack[sizeof($stack)-1])
		{
			$deffer = preg_replace('/:rep:/',"$bullet",$deffer);
			echo $deffer;
			
			for($k=sizeof($stack)-1;(($k>=0) && ($level != $stack[$k]));$k--)
			{
				echo "</li>\n". display_tabs($level) ."</ul>\n";
				$top = array_pop($stack);
				$top1 = array_pop($p_stack);
			}
			$li_id++;
			$deffer = display_tabs($level) . "</li>\n";
			$deffer = $deffer . display_tabs($level) ."<li id=\"li_id$li_id\">:rep:$title";
		}
		elseif($level == $stack[sizeof($stack)-1])
		{
			$deffer = preg_replace('/:rep:/',"$bullet",$deffer);
			echo $deffer;
			$li_id++;
			$deffer = "</li>\n";
			$deffer = $deffer . display_tabs($level) ."<li id=\"li_id$li_id\">:rep:$title";
		}
	}

	$deffer = preg_replace('/:rep:/',"$bullet",$deffer);
	echo $deffer;

	for($i=0;$i<sizeof($stack);$i++)
	{
		echo "</li>\n". display_tabs($level) ."</ul>\n";
	}

	echo "</div>";
}
else
{
	echo "No data in the database";
}
echo "</div>";
echo "</div>";
if($result){$result->free();}
$db->close();

function display_stack($stack)
{
	for($j=0;$j<sizeof($stack);$j++)
	{
		$disp_array = $disp_array . $stack[$j] . ",";
	}
	return $disp_array;
}

function display_tabs($num)
{
	$str_tabs = "";
	
	if($num != 0)
	{
		for($tab=1;$tab<=$num;$tab++)
		{
			$str_tabs = $str_tabs . "\t";
		}
	}
	
	return $str_tabs;
}

?>               
<?php
include ('includes/footer.php');
?>
