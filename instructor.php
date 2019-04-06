<?php
// Turn error reporting on during testing (not production)
//error_reporting(1);


// Database Setup
require('settings.php'); // Settings for Database Login
$db = new mysqli("localhost", $settings['username'], $settings['password'], $settings['dbname']);

// Check for Database access
if ($db->connect_errno) {
    print_response(['DatabaseConnect'=>false,"error"=>"Connect failed: ".$db->connect_error]);
}

// Get the route called in the url
$request_parts = explode('/', $_SERVER['REQUEST_URI']);
$route = array_pop($request_parts);


switch($route){

// Retrieves Password for Admin for password comparison

    case 'adminPassword':
        global $db;
        $password = [];
              
        $sql = "SELECT `Password` FROM `Users` WHERE `User ID` = 1";
        $result = $db->query($sql);

        while ($row = $result -> fetch_assoc()){
           $password[] = $row["Password"];
        }

        print_response($password);
        break;   


// Changes Password for Admin

    case 'updateAdminPassword':
        global $db;
        $newPassword = $_POST['newPassword'];
        $sql = "UPDATE `Users` Set `Password` = '{$newPassword}'  WHERE `User ID` = 1";
        $db->query($sql);
      
        break;
    
// Create New Course - required: course name, password

    case 'newCourse':
        global $db;
    
        $courseName= $_POST['course_name'];
        $pass = $_POST['pass_word'];
    
        $sql = "INSERT INTO `Courses` (`Name`, `Password`) VALUES ('{$courseName}', '{$pass}')";
        $result = $db->query($sql);
        return $result;
        break;

// Create New Group - required: group name, course ID

    case 'newGroup':
        global $db;

        $groupName = $_POST['group_name'];
        $courseID = $_POST['course_ID'];
    
        $sql =  "INSERT INTO `Groups` (`GroupName`, `course ID`) VALUES ('{$groupName}', '{$courseID}')";
        $result = $db->query($sql);
      
        break;

// Create new student - required: group id, student name, course ID

    case 'newStudent':
        global $db;

        $groupID = $_POST['group_ID'];
        $name = $_POST['student_name'];
        $courseID = $_POST['course_ID'];

        $sql = "INSERT INTO `Students` (`Student Name`, `Course ID`, `Group ID`) VALUES ('{$name}', '{$courseID}', '{$groupID}')";
        $db->query($sql);


        $sql = "UPDATE `Courses` Set `Number of Students` = `Number of Students` + 1  WHERE `Course ID` = '{$courseID}'";
        $db->query($sql);

        $sql = "UPDATE `Groups` Set `Number of Students` = `Number of Students` + 1  WHERE `Group ID` = '{$groupID}'";
        $db->query($sql);

        break;

// Return list of all courses

    case 'courseList':
        global $db; 
        $courses = []; // Array for course names
        
       
        $sql = "SELECT * FROM `Courses`";
        $result = $db->query($sql);

        while ($row = $result -> fetch_assoc()){
            $courses[] = $row;
        }
        print_response($courses);
        break;
    
// Return Course Info - required: course ID

    case 'courseInfo':

        global $db; 
        $courses = []; // Array for course names
        $courseID = $_POST['course_ID'];
        $sql = "SELECT * FROM `Courses` WHERE `Course ID` = '{$courseID}'";
        $result = $db->query($sql);

        while ($row = $result -> fetch_assoc()){
            $courses[] = $row;
        }
        print_response($courses);
        break;
    
 // Return Course Name - required: studentID

     case 'studentCourseName':
        global $db;
        $courseName = [];
        $studentID = $_POST['student_ID'];
     

        $sql = "SELECT `Course ID` FROM `Students` WHERE `Student ID` = '{$studentID}'";
        $result = $db->query($sql);
        
        while ($row = $result -> fetch_assoc()){
           $courseID = $row["Course ID"];
        }

        $sql = "SELECT `Name` FROM `Courses` WHERE `Course ID` = '{$courseID}'";
        $result = $db ->query($sql);

        while ($row = $result -> fetch_assoc()){
            $courseName[] = $row;
        }
        print_response($courseName);
        break;   

// Return Course Info - required: group ID

    case 'groupCourseName':
        global $db;
        $courseName = [];
        $groupID = $_POST['group_ID'];

        $sql = "SELECT `Course ID` FROM `Groups` WHERE `Group ID` = '{$groupID}'";
        $result = $db->query($sql);
        
        while ($row = $result -> fetch_assoc()){
           $courseID = $row["Course ID"];
        }

        $sql = "SELECT * FROM `Courses` WHERE `Course ID` = '{$courseID}'";
        $result = $db ->query($sql);

        while ($row = $result -> fetch_assoc()){
            $courseName[] = $row;
        }
        print_response($courseName);
        break;   


// Return Group Name - required: course ID

    case 'groupNameInfo':
        global $db; 
        $courses = []; // Array for course names
        $courseID = $_POST['course_ID'];
        $sql = "SELECT * FROM `Groups` WHERE `Course ID` = '{$courseID}'";
        $result = $db->query($sql);

        while ($row = $result -> fetch_assoc()){
            $courses[] = $row;
        }
        print_response($courses);
        break;

// Return Group Name - required: Student ID
    case 'studentGroupName':
        global $db;
        $groupName = [];
        $studentID = $_POST['student_ID'];
     

        $sql = "SELECT `Group ID` FROM `Students` WHERE `Student ID` = '{$studentID}'";
        $result = $db->query($sql);
        
        while ($row = $result -> fetch_assoc()){
           // echo($row["Group ID"]);
           $groupID = $row["Group ID"];
        }

        $sql = "SELECT `GroupName` FROM `Groups` WHERE `Group ID` = '{$groupID}'";
        $result = $db ->query($sql);

        while ($row = $result -> fetch_assoc()){
            $groupName[] = $row;
        }
        print_response($groupName);
        break;

// Return Group Name - required: Group ID
    case 'groupGroupName':
        global $db;
        $groupName = [];
        $groupID = $_POST['group_ID'];

        $sql = "SELECT `GroupName` FROM `Groups` WHERE `Group ID` = '{$groupID}'";
        $result = $db ->query($sql);

        while ($row = $result -> fetch_assoc()){
            $groupName[] = $row;
        }
        print_response($groupName);

// Return all Student info of group - required: groupID

    case 'groupStudentInfo':
        global $db; 
        $courses = []; // Array for course names
        $groupID = $_POST['group_ID'];
        $sql = "SELECT * FROM `Students` WHERE `Group ID` = '{$groupID}'";
        $result = $db->query($sql);

        while ($row = $result -> fetch_assoc()){
            $courses[] = $row;
        }
        print_response($courses);
        break;

     
// Return individual Student info - required: student ID
    case 'studentInfo':
        global $db;
        $student = [];
        $studentID = $_POST['student_ID'];
        $sql = "SELECT * FROM `Students` WHERE `Student ID` = '{$studentID}'";
        $result = $db->query($sql);

        while ($row = $result -> fetch_assoc()){
            $courses[] = $row;
        }
        print_response($courses);
        break;

// Remove Course

   case 'removeCourse':
        global $db;
        $courseID = $_POST['course_ID'];
        
        $sql = "DELETE FROM `Courses` WHERE `Course ID` = '{$courseID}'";
        $db->query($sql);
        
        $sql = "DELETE FROM `Forms` WHERE `Course ID` = '{$courseID}'";
        $db->query($sql);

        $sql = "DELETE FROM `Groups` WHERE `Course ID` = '{$courseID}'";
        $db->query($sql);

        $sql = "DELETE FROM `Students` WHERE `Course ID` = '{$courseID}'";
        $db->query($sql);

        break;

// Remove Group

   case 'removeGroup':
        global $db;
        $results =[];
        $groupID = $_POST['group_ID'];
        $courseID = $_POST['course_ID'];
        

        $sql = "SELECT `Number of Students` FROM `Groups` WHERE `Group ID` = '{$groupID}'";
        $result = $db->query($sql);

        while ($row = $result -> fetch_assoc()){
            $results[] = $row;
            $numberOfStudents = $row["Number of Students"];
        }

        $sql = "UPDATE `Courses` Set `Number of Students` = `Number of Students` - '{$numberOfStudents}' WHERE `Course ID` = '{$courseID}'";
        $db->query($sql);
       
        $sql = "DELETE FROM `Groups` WHERE `Group ID` = '{$groupID}'";
        $db->query($sql);
           
        $sql = "DELETE FROM `Forms` WHERE `Group ID` = '{$groupID}'";
        $db->query($sql);

        $sql = "DELETE FROM `Students` WHERE `Group ID` = '{$groupID}'";
        $db->query($sql);

        break;

// Remove Student

    case 'removeStudent':
        global $db;
       $studentID = $_POST['student_ID'];
       $courseID = $_POST['course_ID'];
  
        
        $sql = "SELECT `Group ID` FROM `Students` WHERE `Student ID` = '{$studentID}'";
        $result = $db->query($sql);
        
        while ($row = $result -> fetch_assoc()){
           // echo($row["Group ID"]);
           $groupID = $row["Group ID"];
        }
        
  
        $sql = "DELETE FROM `Students` WHERE `Student ID` = '{$studentID}'";
        $db->query($sql);

        $sql = "UPDATE `Courses` Set `Number of Students` = `Number of Students` - 1  WHERE `Course ID` = '{$courseID}'";
        $db->query($sql);

        $sql = "UPDATE `Groups` Set `Number of Students` = `Number of Students` - 1  WHERE `Group ID` = '{$groupID}'";
        $db->query($sql);


        break;

    }


/**
 * Function print_response($response)
 * 
 * This function builds a response object for requests that need a json 
 * data object. 
 */
function print_response($response){
    
  
    if($response['data']){
        $response['data_size'] = sizeof($response['data']);
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}