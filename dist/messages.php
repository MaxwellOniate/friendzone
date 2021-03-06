<?php

require("includes/header.php");

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
    <div class="row">

      <div class="col-lg-4">
        <aside id="messages-aside">
          <a href="messages.php?u=new" class="btn btn-block main-btn mb-4">
            New Message
          </a>

          <div class="conversations card mb-4">
            <ul class="list-group list-group-flush loaded-conversations">
              <?php echo $messageObj->getConvos(); ?>
            </ul>
          </div>

        </aside>
      </div>

      <div class="col-lg-8">
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
              echo "
              <p>Type in a friend's name below.</p>
              <div class='form-group'>
                <div class='form-group'>
                  <input type='text' onkeyup='getUsers(this.value, \"$userLoggedIn\")' name='q' placeholder='Enter Name' class='form-control' autocomplete='off' id='search-text-input'>
                </div>
              </div>
              ";
            }
            ?>

          </div>

          <?php
          if ($userTo != "new") {
            echo "
            <div class='card-footer'>
              <div class='post-message-form py-4'>
                <form method='POST'>
                  <div class='input-group'>
                    <input type='text' name='message-body' class='form-control' placeholder='Write a message.'>
                    <div class='input-group-append'>
                      <input id='message-submit' type='submit' name='post-message' class='btn btn-dark' value='Send'>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            ";
          }
          ?>
        </div>

        <?php
        if ($userTo == "new") {
          echo "
              <div class='results mb-4'></div>
          ";
        }
        ?>
      </div>

    </div>

  </div>
</section>


<script>
  if (document.querySelector('.list-messages')) {
    let div = document.querySelector('.list-messages');
    div.scrollTop = div.scrollHeight;
  }
</script>

<?php require("includes/footer.php"); ?>