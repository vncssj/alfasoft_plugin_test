<?php

function render($function, $variables = null)
{
    call_user_func($function . '_screen', $variables);

    if (wp_doing_ajax()) {
        wp_die();
    }
}

function new_person_screen($person)
{
?>
    <div class="top">
        <h2 class="text-center">Add Person</h2>
        <a href="#" class="link" onclick="redirect('person')">Go back</a>
    </div>
    <form id="add-person">
        <div class="error">Invalid data</div>
        <?php if (isset($person)) {
            echo "<input type='hidden' name='id' value='$person->id' />";
        } ?>
        <div>
            <label for="name">Name</label>
            <input type="text" value="<?= isset($person) ? $person->name : '' ?>" name="name" placeholder="Insert your name" class="form-input" />
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" value="<?= isset($person) ? $person->email : '' ?>" name="email" placeholder="Insert your email" class="form-input" />
        </div>
        <div>
            <button id="btn-save-person">Save</button>
        </div>
    </form>
    <script>
        jQuery("#add-person").submit(function(e) {
            e.preventDefault();
            var form = new FormData(document.querySelector("#add-person"))
            var data = {
                'action': 'new_person',
                'name': form.get('name'),
                'email': form.get('email')
            };

            if (form.get('id')) {
                data.id = form.get('id');
            }

            jQuery.post(ajaxurl, data, function(response) {
                if (response.status == 'sucess') {
                    redirect('person')
                    return
                }
                jQuery("#person-error").show()
            });
        })
    </script>
<?php
}

function person_screen($people)
{
?>
    <div id="content" class="alfasoft-content">
        <div class="top">
            <h2 class="text-center">People</h2>
            <a href="#" class="link" onclick="redirect('new_person')">Add Person</a>
        </div>
        <table cellspacing="0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($people as $person) : ?>
                    <tr>
                        <td><?= $person->name ?></td>
                        <td><?= $person->email ?></td>
                        <td>
                            <a href="#" class="link" onclick="redirect('new_person', <?= $person->id ?>)">Edit</a>
                            <a href="#" class="link" onclick="redirect('new_contact', <?= $person->id ?>)">Add Contact</a>
                            <a href="#" class="link" onclick="redirect('show_person', <?= $person->id ?>)">Details</a>
                            <a href="#" class="link" onclick="redirect('delete_person', <?= $person->id ?>)">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php
}

function new_contact_screen($params)
{
    if (isset($params['person_id'])) {
        $person_id = $params['person_id'];
    } else {
        $contact = $params['contact'];
        $person_id = $contact->person_id;
    }
?>
    <div class="top">
        <h2 class="text-center">Add Contact</h2>
        <a href="#" class="link" onclick="redirect('person')">Go back</a>
    </div>
    <form id="add-contact">
        <div class="error">Invalid data</div>
        <?php if (isset($contact)) {
            echo "<input type='hidden' name='id' value='$contact->id' />";
        }
        echo "<input type='hidden' name='person_id' value='$person_id' />";
        ?>
        <div>
            <label for="country_code">Country</label>
            <input type="text" value="<?= isset($contact) ? $contact->country_code : '' ?>" name="country_code" placeholder="Insert country Code" class="form-input" />
        </div>
        <div>
            <label for="number">Number</label>
            <input type="phone" value="<?= isset($contact) ? $contact->number : '' ?>" name="number" placeholder="Insert number" class="form-input" />
        </div>
        <div>
            <button id="btn-save-contact">Save</button>
        </div>
    </form>
    <script>
        jQuery("#add-contact").submit(function(e) {
            e.preventDefault();
            var form = new FormData(document.querySelector("#add-contact"))
            var data = {
                'action': 'new_contact',
                'person_id': form.get('person_id'),
                'country_code': form.get('country_code'),
                'number': form.get('number')
            };

            if (form.get('id')) {
                data.id = form.get('id');
            }

            jQuery.post(ajaxurl, data, function(response) {
                if (response.status == 'sucess') {
                    redirect('person')
                    return
                }
                jQuery("#contact-error").show()
            });
        })
    </script>
<?php
}

function show_person_screen($person)
{
?>
    <div class="top">
        <h2 class="text-center"><?= $person->name ?> - <small><?= $person->email ?></small>
        </h2>
        <a href="#" class="link" onclick="redirect('person')">Go back</a>
    </div>
    <table cellspacing="0">
        <thead>
            <tr>
                <th>Country Code</th>
                <th>Number</th>
                <th>actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($person->contacts as $contact) : ?>
                <tr>
                    <td><?= $contact->country_code ?></td>
                    <td><?= $contact->number ?></td>
                    <td>
                        <a href="#" class="link" onclick="redirect('edit_contact', <?= $contact->id ?>)">Edit</a>
                        <a href="#" class="link" onclick="redirect('delete_contact', <?= $contact->id ?>)">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php
}
