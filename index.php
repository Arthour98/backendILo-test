<?php
session_start();
$json_file = file_get_contents("tasks.json");
$tasks = json_decode($json_file, true); //legit data !!

$tasks_arr = [];   ///////->kalou kakou tha xrhsimopoihsw allo array oxi to original,dikh mou prosegish !
$pointer_id = 0;
if (is_array($tasks))   //////->kaluptw to senario opou to task einai akoma adeio !!!
{
    foreach ($tasks as $task) {
        $tasks_arr[] = $task;
    }
    $pointer_id = $tasks_arr[count($tasks_arr) - 1]["id"];
}

$err_msg = "";

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["title"])
    && isset($_POST["description"])

) {
    $title = htmlspecialchars($_POST["title"]);
    $description = htmlspecialchars(($_POST["description"]));
    $id = $pointer_id + 1;
    $done = false;
    $task_placeholder = array("id" => $id, "title" => $title, "description" => $description, "done" => $done);

    if (strlen($title) >= 3)  //validation
    {
        $task = array($tasks_placeholder);

        if (is_array($tasks)) {
            $task =  array(...$tasks, $task_placeholder);
        } else {
            $task = array($task_placeholder);
        }
        $converted_task = json_encode($task, JSON_PRETTY_PRINT);
        if (file_put_contents("tasks.json", $converted_task)) {
            header("location:index.php");
            exit;
        }
    } else {
        $_SESSION["err_msg"] = "Length must be at least 3 chars long";
        header("location:index.php");
        exit;
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backend Test</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="task-container">
        <div class="form-col">
            <h2>Add you tasks</h2>
            <form action="index.php" method="post" id="task-form">
                <div class="flex">
                    <label for="task-input" class="task-label">Title :</label>
                    <input type="text" name="title" placeholder="title" id="task-input" />
                </div>
                <div class="flex">
                    <label for="task-desc" class="task-label">Description :</label>
                    <textarea id="task-desc" name="description"
                        placeholder="description"></textarea>
                </div>
                <?php if (!empty($_SESSION["err_msg"])):; ?>
                    <?php echo '<p class="error">' . $_SESSION["err_msg"] . '</p>'; ?>
                    <?php unset($_SESSION["err_msg"]); ?>
                <?php endif; ?>

                <div class="flex end-align">
                    <input type="submit" value="Add" id="add-button" name="task-submit">
                </div>
            </form>
        </div>

        <div class="task-col">
            <?php if (empty($tasks_arr)): ?>
                <p>No Tasks,No Worries</p>
            <?php else:; ?>
                <?php foreach ($tasks_arr as $task): ?>
                    <div class="task-line">
                        <p class="<?php echo $task["done"] === true ? "title done" : "title"; ?>"><?php echo $task["title"] . ":"  ?></p>
                        <p class="<?php echo $task["done"] === true ? "desc done" : "desc"; ?>"><?php echo  $task["description"]; ?></p>
                        <form action="toggle.php" method="POST" class="status-form">
                            <?php if ($task["done"] === false): ?>
                                <input type="checkbox" data-id="<?php echo $task["id"]; ?>" class="check" name="status">
                            <?php else: ?>
                                <input type="checkbox" data-id="<?php echo $task["id"]; ?>" class="check" name="status" checked>
                            <?php endif; ?>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>


    <script src="script.js"></script>
</body>

</html>