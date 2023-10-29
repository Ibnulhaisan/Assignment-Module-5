<div style="border-bottom: 1px solid; border-color:#eee; padding-bottom: 30px; margin-bottom:30px;">
    <div class="float-left">
        <p>
            <a href="/index.php?task=report">All Students</a> 
            <a href="/index.php?task=seed" style="margin-left: 10px;">Seed</a>
			
            <a href="/index.php?task=add" style="margin-left: 390px;" >Add Student</a>	
			
           
        </p>
    </div>
    <div class="float-right">
		<?php
            if ( !isset($_SESSION['loggedin']) || !$_SESSION['loggedin'] ):
			?>
            <a href="/auth.php">Log In</a>
		<?php
		else:
			?>
            <a href="/auth.php?logout=true"><button type="button" class="btn btn-success">Log Out</button> </a>
		<?php
		endif;
		?>
    </div>
    <!-- <p></p> -->
</div>

