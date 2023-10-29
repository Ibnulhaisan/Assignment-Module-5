<?php
// session_start(); // Start a session if not already started

define('DB_NAME', 'data/db.txt');

function seed() {
    $data = array(
        array(
            'id' => 1,
            'fname' => 'Helal',
            'lname' => 'Khan',
            'roll' => '11'
        ),
        array(
			'id'    => 2,
			'fname' => 'Abdur',
			'lname' => 'Rahim',
			'roll'  => '12'
		),
		array(
			'id'    => 3,
			'fname' => 'Rajoan',
			'lname' => 'Ahmed',
			'roll'  => '13'
		),
		array(
			'id'    => 4,
			'fname' => 'Shanto',
			'lname' => 'Sorkar',
			'roll'  => '14'
		),
		array(
			'id'    => 5,
			'fname' => 'Rohan',
			'lname' => 'Ahmed',
			'roll'  => '15'
		),
	);

  $serializedData = serialize($data);

    if (file_put_contents(DB_NAME, $serializedData, LOCK_EX) === false) {
        echo "Failed to write data to the file.";
    }
	// session_destroy();
}

function generateReport() {
    $serializedData = file_get_contents(DB_NAME);

    if ($serializedData === false) {
        echo "Failed to read data from the file.";
        return;
    }

    $students = unserialize($serializedData);
	?>
     <table>
        <tr>
            <th>Name</th>
            <th>Roll</th>
			<?php if ( isAdmin() || isEditor() ): ?>
                <th width="25%">Action</th>
			<?php endif; ?>
        </tr>
		<?php
		foreach ( $students as $student ) {
			?>
            <tr>
                <td><?php printf( '%s %s', $student['fname'], $student['lname'] ); ?></td>
                <td><?php printf( '%s', $student['roll'] ); ?></td>
				<?php if ( isAdmin() ): ?>
                    <td><?php printf( '<a href="/index.php?task=edit&id=%s"><button type="button" class="btn btn-primary">Edit</button></a>  <a class="delete" href="/index.php?task=delete&id=%s"><button type="button" class="btn btn-danger">Delete</button></a>', $student['id'], $student['id'] ); ?></td>
				<?php elseif ( isEditor() ): ?>
                    <td><?php printf( '<a href="/index.php?task=edit&id=%s">Edit</a>', $student['id'] ); ?></td>
				<?php endif; ?>
            </tr>
			<?php
		}
		?>

    </table>
	<?php
}

function addStudent( $fname, $lname, $roll ) {
	$found          = false;
	$serialziedData = file_get_contents( DB_NAME );
	$students       = unserialize( $serialziedData );
	foreach ( $students as $_student ) {
		if ( $_student['roll'] == $roll ) {
			$found = true;
			break;
		}
	}
	if ( ! $found ) {
		$newId   = getNewId( $students );
		$student = array(
			'id'    => $newId,
			'fname' => $fname,
			'lname' => $lname,
			'roll'  => $roll
		);
		array_push( $students, $student );
		$serializedData = serialize( $students );
		file_put_contents( DB_NAME, $serializedData, LOCK_EX );

		return true;
	}

	return false;
}

function getStudent( $id ) {
	$serialziedData = file_get_contents( DB_NAME );
	$students       = unserialize( $serialziedData );
	foreach ( $students as $student ) {
		if ( $student['id'] == $id ) {
			return $student;
		}
	}

	return false;
}

function updateStudent( $id, $fname, $lname, $roll ) {
	$found          = false;
	$serialziedData = file_get_contents( DB_NAME );
	$students       = unserialize( $serialziedData );
	foreach ( $students as $_student ) {
		if ( $_student['roll'] == $roll && $_student['id'] != $id ) {
			$found = true;
			break;
		}
	}
	if ( ! $found ) {
		$students[ $id - 1 ]['fname'] = $fname;
		$students[ $id - 1 ]['lname'] = $lname;
		$students[ $id - 1 ]['roll']  = $roll;
		$serializedData               = serialize( $students );
		file_put_contents( DB_NAME, $serializedData, LOCK_EX );

		return true;
	}

	return false;
}

function deleteStudent( $id ) {
	$serialziedData = file_get_contents( DB_NAME );
	$students       = unserialize( $serialziedData );

	foreach ( $students as $offset => $student ) {
		if ( $student['id'] == $id ) {
			unset( $students[ $offset ] );
		}

	}
	$serializedData = serialize( $students );
	file_put_contents( DB_NAME, $serializedData, LOCK_EX );
}

function printRaw() {
	$serialziedData = file_get_contents( DB_NAME );
	$students       = unserialize( $serialziedData );
	print_r( $students );
}

function getNewId( $students ) {
	$maxId = max( array_column( $students, 'id' ) );

	return $maxId + 1;
}


function isAdmin() {
    if (isset($_SESSION['role'])) {
        return ('admin' == $_SESSION['role']);
    }
    return false;
}

function isEditor() {
    if (isset($_SESSION['role'])) {
        return ('editor' == $_SESSION['role']);
    }
    return false;
}

function hasPrivilege() {
    return (isAdmin() || isEditor());
}
?>
