<?php

require("includes/header.php");
require("includes/classes/Message.php");

$messageObj = new Message($con, $userLoggedIn);

if (isset($_GET['u'])) {
  $userTo = $_GET['u'];
} else {
  $userTo = $messageObj->getMostRecentUser();
  if ($userTo == false) {
    $userTo = 'new';
  }
}

if ($userTo != "new") {
  $userToObj = new User($con, $userTo);
}

if (isset($_POST['post-message'])) {
  if (isset($_POST['message-body'])) {
    $body = $_POST['message-body'];
    $date = date("Y-m-d H:i:s");
    $messageObj->sendMessage($userTo, $body, $date);
    header("Location: messages.php?u=$userTo");
  }
}

?>

<section id="messages">
  <div class="container">
    <div class="card mb-4">
      <div class="card-header">
        <h1 class="card-title">
          <?php
          if ($userTo != "new") {
            echo "
            You and <a href='$userTo'>" . $userToObj->getFullName() . "</a>";
          } else {
            echo "Send a Message";
          }
          ?>
        </h1>
      </div>
      <div class="card-body">
        <?php
        if ($userTo != "new") {
          echo "
            <ul class='list-group list-messages'>
            " . $messageObj->getMessages($userTo) . "
            </ul>
          ";
        } else {
          echo "<h3>New Message</h3>";
        }
        ?>

      </div>
      <div class="card-footer">
        <div class="post-message-form py-4">
          <form action="" method="POST">
            <?php
            if ($userTo == "new") {
              echo "
                <p>Select the friend you would like to message</p>

                <div class='form-group'>
                  <div class='input-group'>
                    <div class='input-group-prepend'>
                      <span class='input-group-text'>To:</span>
                    </div>
                    <input type='text' placeholder='Enter Name' class='form-control'>
                  </div>
                </div>

                <div class='results'>
                </div>
                ";
            } else {
              echo "
              <div class='input-group'>
                <input type='text' name='message-body' class='form-control' placeholder='Write a message.'>
                <div class='input-group-append'>
                  <input id='message-submit' type='submit' name='post-message' class='btn btn-dark' value='Send'>
                </div>
              </div>
              ";
            }
            ?>
          </form>
        </div>
      </div>
    </div>

    <script>
      let div = document.querySelector('.list-messages');
      div.scrollTop = div.scrollHeight;
    </script>

  </div>
</section>


<?php require("includes/footer.php"); ?>