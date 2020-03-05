<?php require('includes/header.php'); ?>

<section id="friend-requests">
  <div class="container">
    <div class="card">
      <div class="card-header">Friend Requests</div>
      <div class="card-body">
        <?php
        $query = $con->prepare("SELECT * FROM friend_requests WHERE user_to = :un");
        $query->execute([':un' => $userLoggedIn]);

        if ($query->rowCount() == 0) {
          echo "You have no new friend requests.";
        } else {
          while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
            $userFrom = $row['user_from'];
            $userFromObj = new User($con, $userFrom);
            $userFromFriendArray = $userFromObj->getFriendArray();

            echo "
            <div class='card my-3'>
              <div class='card-body'>
                <div class='media align-items-center'>
                  <img src='" . $userFromObj->getProfilePic() . "' alt='" . $userFromObj->getFullName() . "' class='img-fluid friend-request-profilePic'>
                  <div class='media-body'>
                    <a href='" . $userFromObj->getUsername() . "'>
                    " . $userFromObj->getFullName() . "
                    </a>
                    <form id='request-$id'>
                      <input type='hidden' value='$id'>
                      <button onclick='respondFR(this)' name='accept-$id' class='btn main-btn btn-sm'>Accept</button>
                      <input type='hidden' value='$id'>
                      <button onclick='respondFR(this)' name='decline-$id' class='btn btn-outline-secondary btn-sm rounded-0'>Decline</button>

                    </form>
                    
                  </div>
                </div>
              </div>
            </div>
            ";
          }
        }

        ?>
      </div>
    </div>

  </div>
</section>

<script>
  let userLoggedIn = '<?php echo $userLoggedIn; ?>';

  function respondFR(responseBtn) {
    let form = $(responseBtn).parent();
    let requestID = $(responseBtn).prev().val();
    let submit = $(responseBtn).attr("name");
    let profile = $(responseBtn).parent().prev().attr('href');
    let fullName = $(responseBtn).parent().prev().text();
    let card = $(responseBtn).parent().parent().parent().parent().parent();

    $(form).one('submit', function(e) {
      e.preventDefault();
      $.post("ajax/respondFR.php", {
        requestID: requestID,
        submit: submit,
        profile: profile,
        userLoggedIn: userLoggedIn
      }).done(function(data) {
        $(card).replaceWith(data);
      });
    });
  }
</script>


<?php require('includes/footer.php'); ?>