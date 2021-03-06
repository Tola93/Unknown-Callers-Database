<?php session_start(); ?>
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
  <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
      <ul class="navigation_items navbar-nav me-auto">
          <li class="nav-item">
              <a class="nav-link" href="index.php">Főoldal</a>
          </li>
          <?php
          if(isset($_SESSION['is_logged_in']) == "logged_in") {
            echo '<li class="nav-item"><a class="nav-link" href="incoming_calls.php">Hívások</a></li>';
            echo '<li class="nav-item"><a class="nav-link" href="calling_numbers.php">Hívó számok</a></li>';
            echo '<li class="nav-item"><a class="nav-link" href="called_numbers.php">Hívott számok</a></li>';
          }
          ?>
      </ul>
  </div>
  <div class="mx-auto order-0">
      <a class="navbar-brand mx-auto" href="index.php"><img src="./../resources/images/mobile-phone-icon-1.png" width="50" height="50"></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
          <span class="navbar-toggler-icon"></span>
      </button>
  </div>
  <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
      <ul class="navigation_items navbar-nav ms-auto">
          <?php
          if(isset($_SESSION['is_logged_in']) == "logged_in") {
            echo '
            <li class="nav-item">
              <form class="form-inline my-2 my-lg-0 input-group">
                <input class="form-control mr-sm-2" type="search" placeholder="Keresés" aria-label="Keresés">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Keresés</button>
              </form>
            </li>';
          }
          ?>
          <?php
          if(isset($_SESSION['is_logged_in']) == "logged_in") {
            echo '<li class="nav-item mx-2  mt-1"><a href="./../controller/sign_out_controller.php" class="nav-link">Kijelentkezés</a></li>';
          } else {
            echo '<li class="nav-item mx-2  mt-1"><a href="sign_in.php" class="nav-link">Bejelentkezés</a></li>';
          }
          ?>
      </ul>
  </div>
</nav>
