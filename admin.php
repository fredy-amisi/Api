case "POST":
    if (isset($_GET['action']) && $_GET['action'] === 'upload_course') {
        if (isset($_FILES['course_image']) && isset($_POST['course_name']) && isset($_POST['course_code']) && isset($_POST['description']) && isset($_POST['teacher_id'])) {
            $courseName = $_POST['course_name'];
            $courseCode = $_POST['course_code'];
            $description = $_POST['description'];
            $teacherId = $_POST['teacher_id'];

            $image = $_FILES['course_image'];
            $imageName = $image['name'];
            $imageTmpName = $image['tmp_name'];
            $imageSize = $image['size'];
            $imageError = $image['error'];
            $imageType = $image['type'];

            $imageExt = explode('.', $imageName);
            $imageActualExt = strtolower(end($imageExt));

            $allowed = array('jpg', 'jpeg', 'png');

            if (in_array($imageActualExt, $allowed)) {
                if ($imageError === 0) {
                    if ($imageSize < 1000000) {
                        $imageNewName = uniqid('', true) . "." . $imageActualExt;
                        $imageDestination = 'uploads/' . $imageNewName;
                        move_uploaded_file($imageTmpName, $imageDestination);

                        $sql = "INSERT INTO courses (course_name, course_code, description, teacher_id, course_image) VALUES (:course_name, :course_code, :description, :teacher_id, :course_image)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':course_name', $courseName);
                        $stmt->bindParam(':course_code', $courseCode);
                        $stmt->bindParam(':description', $description);
                        $stmt->bindParam(':teacher_id', $teacherId);
                        $stmt->bindParam(':course_image', $imageNewName);

                        if ($stmt->execute()) {
                            $response = ['success' => true, 'message' => 'Course uploaded successfully.'];
                            echo json_encode($response);
                        } else {
                            $response = ['success' => false, 'message' => 'Failed to upload course.'];
                            echo json_encode($response);
                        }
                    } else {
                        $response = ['success' => false, 'message' => 'Your file is too big.'];
                        echo json_encode($response);
                    }
                } else {
                    $response = ['success' => false, 'message' => 'There was an error uploading your file.'];
                    echo json_encode($response);
                }
            } else {
                $response = ['success' => false, 'message' => 'You cannot upload files of this type.'];
                echo json_encode($response);
            }
        } else {
            $response = ['success' => false, 'message' => 'Missing required fields.'];
            echo json_encode($response);
        }
    } else {
        // Handle other POST requests
    }
    break;
