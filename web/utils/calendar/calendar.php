<?php
// Get values from query string
$day = $_GET["day"];
$month = $_GET["month"];
$year = $_GET["year"];
$sel = $_GET["sel"];
$what = $_GET["what"];
$field = $_GET["field"];
$form = $_GET["form"];

if($day == "") $day = date("j");

if($month == "") $month = date("m");

if($year == "") $year = date("Y");

$currentTimeStamp = strtotime("$year-$month-$day");
$monthName = date("F", $currentTimeStamp);
$numDays = date("t", $currentTimeStamp);
$counter = 0;
/*$numEventsThisMonth = 0;
$hasEvent = false;
$todaysEvents = "";*/
$month_arr = array( 1 => 'Январь', 2 => 'Февраль', 3 => 'Март', 4 => 'Апрель', 5 => 'Май', 6 => 'Июнь', 7 => 'Июль', 8 => 'Август', 9 => 'Сентябрь', 10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь' );

?>
<html>
<head>
<title>MyCalendar</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="calendar.css">
<script language="javascript">
    function goLastMonth(month,year,form,field)
    {
        // If the month is January, decrement the year.
        if(month == 1)
    {
    --year;
    month = 13;
    }       
        document.location.href = 'calendar.php?month='+(month-1)+'&year='+year+'&form='+form+'&field='+field;
    }
   
    function goNextMonth(month,year,form,field)
    {
        // If the month is December, increment the year.
        if(month == 12)
    {
    ++year;
    month = 0;
    }   
        document.location.href = 'calendar.php?month='+(month+1)+'&year='+year+'&form='+form+'&field='+field;
    }
   
    function sendToForm(val,field,form)
    {
        // Send back the date value to the form caller.
        eval("opener.document." + form + "." + field + ".value='" + val + "'");
        window.close();
    }
</script>
</head>
<body style="margin:0px 0px 0px 0px" class="body">
<table width='175' border='0' cellspacing='0' cellpadding='0' class="body">
    <tr>
        <td width='25' colspan='1'>
        <input type='button' class='button' value=' < ' onClick='<?php echo "goLastMonth($month,$year,\"$form\",\"$field\")"; ?>'>
        </td>
        <td width='125' align="center" colspan='5'>
        <span class='title'><?php echo $month_arr[date("n", $currentTimeStamp)] . " " . $year; ?></span><br>
        </td>
        <td width='25' colspan='1' align='right'>
        <input type='button' class='button' value=' > ' onClick='<?php echo "goNextMonth($month,$year,\"$form\",\"$field\")"; ?>'>
        </td>
    </tr>
    <tr>
        <td class='head' align="center" width='25'>Пн</td>
        <td class='head' align="center" width='25'>Вт</td>
        <td class='head' align="center" width='25'>Ср</td>
        <td class='head' align="center" width='25'>Чт</td>
        <td class='head' align="center" width='25'>Пт</td>
        <td class='head' align="center" width='25'><font color='red'>Сб</font></td>
        <td class='head' align="center" width='25'><font color='red'>Вс</font></td>
    </tr>
    <tr>
<?php
    for($i = 1; $i < $numDays+1; $i++, $counter++)
    {
        $timeStamp = strtotime("$year-$month-$i");
        if($i == 1)
        {
        // Workout when the first day of the month is
        $firstDay = date("N", $timeStamp);
       
        for($j = 1; $j < $firstDay; $j++, $counter++)
        echo "<td> </td>";
        }
       
        if($counter % 7 == 0)
        echo "</tr><tr>";
       
        if(date("N", $timeStamp) == 6 || date("N", $timeStamp) == 7)

        $class = "class='weekend'";
        else
        if($i == date("d") && $month == date("m") && $year == date("Y"))
        $class = "class='today'";
        else
        $class = "class='normal'";
       
        echo "<td class='tr' bgcolor='#ffffff' align='center' width='25'><a class='buttonbar' href='#' onclick=\"sendToForm('".sprintf("%04d-%02d-%02d", $year, $month, $i)."','$field','$form');\"><font $class>$i</font></a></td>";
    }
?>
    </tr>
</table>
</body>
</html>