<?php

require_once 'conn.php';


// Function to fetch and display members 
function display_members($pdo, $parent_id = 0, $level = 0) {
    $stmt = $pdo->prepare("SELECT * FROM members WHERE parent_id = :parent_id");
    $stmt->execute(array(':parent_id'=>$parent_id));

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if($level == 0){
            $list_style_type='disc';
        }elseif($level == 1){
            $list_style_type='circle';
        }elseif($level == 2){
            $list_style_type='square';
        }
        echo "<li style='margin-left: " . $level * 20 . "px;list-style-type:".$list_style_type.";'>";
        echo $row['name'];
        display_members($pdo, $row['id'], $level + 1);
        echo "</li>";
    }
}

if($_REQUEST['type']=='dropdown_members')
{
    echo "<option value='0'>Root</option>";
    $stmt = $pdo->query("SELECT id, name FROM members");
    while ($row = $stmt->fetch()) {
        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
    }    
}
else
{
    display_members($pdo);    
}    
    
?>
