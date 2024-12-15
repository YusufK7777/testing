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
  
?>

<!DOCTYPE html>
<html>
<head>
    <title>Member List</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    </head>
<body>
    <h1>Members</h1>
    <ul id="members_list">
        <?php display_members($pdo); ?>
    </ul>

    <a href="#add_member_div" id="add_member" data-fancybox><button>Add Member</button></a>

    <div id="add_member_div" style="display: none;">
        <form id="add_member_form">
            <h5>Add Member</h5>
            <hr>
            <label for="parent_id"><b>Parent</b></label>
            <br>
            <select name="parent_id" id="parent_id" style="width:200px;">
                <option value="0">Root</option>
                <?php
                $stmt = $pdo->query("SELECT id, name FROM members");
                while ($row = $stmt->fetch()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                }
                ?>
            </select>
            <br><br>
            <label for="name"><b>Name*</b></label>
            <br>
            <input type="text" name="name" id="name" minlength="1" required style="width:200px;">
            <br><br>
            <button type="button" data-fancybox-close title="Close">Close</button>
            <button type="submit">Save Changes</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
           
            $('#add_member').fancybox();

            $('#add_member_form').submit(function(event) {
                event.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'add_member.php',
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(response) {
                        if(response.response == '1') {                      
                            alert(response.msg);                            
                        }
                        else {
                            alert(response.msg);
                        }
                        $.fancybox.close();
                        $('#members_list').load('get_member.php?type=display_members');
                        $('#parent_id').load('get_member.php?type=dropdown_members');
                        $('#name').val('');
                    },
                    error: function(error) {
                        alert('Error submitting form');
                    }
                });
            });
        });
    </script>
</body>
</html>
