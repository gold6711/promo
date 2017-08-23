            <table class="table table-striped">
<thead><th>ID заявки</th><th>Проект</th><th>Сотрудник</th><th>Должность</th><th>Дата выплаты</th><th>Сумма</th><th>Комментарий</th></thead>
<tbody>
<?//unset($tr);$_GET['paid']=1;$_GET['tab']='kassa';include 'kassa.php'
unset($dop);
if(user('status')>1){$dop="where office=".user('office');}
$f = mysqli_query($on,"SELECT * FROM `kassa` $dop order by oid desc");
while($fm = mysqli_fetch_array($f)){
$p=users($fm['user'],'status');
$post="<a href=payments?post=$p><span class=\"label label-".table('posts','id',$p,'color')."\">".table('posts','id',$p,'name')."</span></a>";
echo "<tr><td>$fm[ord]</td><td>".table('resultats','id',$fm['oid'],'project')."</td><td>".users($fm['user'],'name')."</td><td>$post</td><td>".date('j.m.Y, H:i',$fm['cdate'])."</td><td>$fm[summa]</td><td>$fm[comment]</td></tr>";
}
?>
</tbody>
</table>