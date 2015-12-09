<br/>
<table>
    <th><td><?=__('Username');?></td><td><?=__('Name');?></td><td><?=__('Surname');?></td><td><?=__('E-mail');?></td></th>
<?php foreach ($users as $user):?>
    <tr>
        <td><?=$user['User']['id'];?></td>
        <td><?=$user['User']['username'];?></td>
        <td><?=$user['User']['name'];?></td>
        <td><?=$user['User']['surname'];?></td>
        <td><?=$user['User']['mail'];?></td>
        
    </tr>
<?php endforeach; ?>
</table>

