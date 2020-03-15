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
    <div class="row">

      <div class="col-lg-4">
        <aside id="messages-aside">
          <div class="card user-details mb-4">
            <div class="card-body">

              <div class="media">
                <a href="<?php echo $user->getUsername(); ?>" class="pr-3">
                  <img src="<?php echo $user->getProfilePic(); ?>" alt="<?php echo $user->getFullName(); ?>" class="img-fluid user-details-img">
                </a>

                <div class="media-body">
                  <a href="<?php echo $user->getUsername(); ?>" class="user-full-name"><?php echo $user->getFullName(); ?></a>
                  <p class="user-data">Posts: <?php echo $user->getPostCount(); ?></p>
                  <p class="user-data">Likes: <?php echo $user->getLikeCount(); ?></p>
                  <p class="user-data">Friends: <?php echo $user->getFriendCount(); ?></p>
                </div>
              </div>


            </div>

          </div>
          <div class="card conversations mb-4">
            <div class="card-header">
              <h3 class="card-title">Conversations</h3>
            </div>
            <div class="card-body">
              <div class="loaded-conversations">
                <?php echo $messageObj->getConvos(); ?>
              </div>
              <a href="messages.php?u=new">New Message</a>
            </div>
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
      </div>

    </div>

  </div>
</section>


<script>
  let div = document.querySelector('.list-messages');
  div.scrollTop = div.scrollHeight;
</script>

<?php require("includes/footer.php"); ?>