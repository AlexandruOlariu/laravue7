<!--check if the current user can update an entry -->
<?php global $user1,$user2,$user3;?>
<?php
/** @var $flowers  comes from FlowersController via index method*/
if (count($flowers)!=0)
        {

        ?>
    <?php $user1=0;  ?>
    @can('update', $flowers[0])
   <?php $user1=1;?>
    @endcan

<!--check if the current user can delete an entry -->
    <?php $user2=0;  ?>
    @can('delete', $flowers[0])
        <?php $user2=1;?>
    @endcan

<!--check if the current user can create an entry -->
<?php $user3=0;  ?>
@can('create', $flowers[0])
    <?php $user3=1;?>
@endcan

<?php }
    else{  $user3=0;  ?>
@can('create', App\Flower::class)
    <?php $user3=1;?>
    @endcan

    <?php

    }

    ?>




