<?php
// config/database.php - Konfigurasi Database
class Database
{
    private $host = 'localhost';
    private $db_name = 'portfolio';
    private $username = '';
    private $password = '';
    private $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

// models/PersonalInfo.php - Model untuk Personal Info
class PersonalInfo
{
    private $conn;
    private $table = 'personal_info';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getPersonalInfo()
    {
        $query = "SELECT * FROM " . $this->table . " LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePersonalInfo($data)
    {
        $query = "UPDATE " . $this->table . " SET 
                  name = :name, 
                  profession = :profession, 
                  bio = :bio,
                  phone = :phone,
                  email = :email,
                  location = :location,
                  profile_image = :profile_image,
                  updated_at = NOW()
                  WHERE id = 1";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':name' => $data['name'],
            ':profession' => $data['profession'],
            ':bio' => $data['bio'],
            ':phone' => $data['phone'],
            ':email' => $data['email'],
            ':location' => $data['location'],
            ':profile_image' => $data['profile_image']
        ]);
    }
}

// models/Project.php - Model untuk Projects
class Project
{
    private $conn;
    private $table = 'projects';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllProjects()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProjectById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createProject($data)
    {
        $query = "INSERT INTO " . $this->table . " 
                  (title, description, image, demo_url, github_url, status, featured) 
                  VALUES (:title, :description, :image, :demo_url, :github_url, :status, :featured)";

        $stmt = $this->conn->prepare($query);

        if ($stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':image' => $data['image'],
            ':demo_url' => $data['demo_url'],
            ':github_url' => $data['github_url'],
            ':status' => $data['status'],
            ':featured' => $data['featured']
        ])) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function updateProject($id, $data)
    {
        $query = "UPDATE " . $this->table . " SET 
                  title = :title,
                  description = :description,
                  image = :image,
                  demo_url = :demo_url,
                  github_url = :github_url,
                  status = :status,
                  featured = :featured,
                  updated_at = NOW()
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':id' => $id,
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':image' => $data['image'],
            ':demo_url' => $data['demo_url'],
            ':github_url' => $data['github_url'],
            ':status' => $data['status'],
            ':featured' => $data['featured']
        ]);
    }

    public function deleteProject($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getProjectTechnologies($project_id)
    {
        $query = "SELECT * FROM project_technologies WHERE project_id = :project_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':project_id', $project_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addProjectTechnology($project_id, $technology)
    {
        $query = "INSERT INTO project_technologies (project_id, technology_name) VALUES (:project_id, :technology)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':project_id' => $project_id,
            ':technology' => $technology
        ]);
    }

    public function deleteProjectTechnologies($project_id)
    {
        $query = "DELETE FROM project_technologies WHERE project_id = :project_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':project_id', $project_id);
        return $stmt->execute();
    }
}

// models/Article.php - Model untuk Articles
class Article
{
    private $conn;
    private $table = 'articles';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllArticles()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPublishedArticles()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE status = 'published' ORDER BY published_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createArticle($data)
    {
        $query = "INSERT INTO " . $this->table . " 
                  (title, slug, content, excerpt, featured_image, status, published_at) 
                  VALUES (:title, :slug, :content, :excerpt, :featured_image, :status, :published_at)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':title' => $data['title'],
            ':slug' => $data['slug'],
            ':content' => $data['content'],
            ':excerpt' => $data['excerpt'],
            ':featured_image' => $data['featured_image'],
            ':status' => $data['status'],
            ':published_at' => $data['status'] === 'published' ? date('Y-m-d H:i:s') : null
        ]);
    }
}

// models/Skill.php - Model untuk Skills
class Skill
{
    private $conn;
    private $table = 'skills';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllSkills()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY category, skill_name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSkillsByCategory()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY category, skill_name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $grouped = [];
        foreach ($skills as $skill) {
            $grouped[$skill['category']][] = $skill;
        }
        return $grouped;
    }

    public function createSkill($data)
    {
        $query = "INSERT INTO " . $this->table . " 
                  (skill_name, category, proficiency_level, icon) 
                  VALUES (:skill_name, :category, :proficiency_level, :icon)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':skill_name' => $data['skill_name'],
            ':category' => $data['category'],
            ':proficiency_level' => $data['proficiency_level'],
            ':icon' => $data['icon']
        ]);
    }
}

// controllers/AdminController.php - Controller untuk Admin Panel
class AdminController
{
    private $db;
    private $personalInfo;
    private $project;
    private $article;
    private $skill;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();

        $this->personalInfo = new PersonalInfo($this->db);
        $this->project = new Project($this->db);
        $this->article = new Article($this->db);
        $this->skill = new Skill($this->db);
    }

    public function dashboard()
    {
        $data = [
            'total_projects' => count($this->project->getAllProjects()),
            'total_articles' => count($this->article->getAllArticles()),
            'total_skills' => count($this->skill->getAllSkills()),
            'recent_projects' => array_slice($this->project->getAllProjects(), 0, 5),
            'recent_articles' => array_slice($this->article->getAllArticles(), 0, 5)
        ];

        return $data;
    }

    public function getPersonalInfo()
    {
        return $this->personalInfo->getPersonalInfo();
    }

    public function updatePersonalInfo($data)
    {
        return $this->personalInfo->updatePersonalInfo($data);
    }

    public function getAllProjects()
    {
        return $this->project->getAllProjects();
    }

    public function createProject($data, $technologies = [])
    {
        $project_id = $this->project->createProject($data);

        if ($project_id && !empty($technologies)) {
            foreach ($technologies as $tech) {
                $this->project->addProjectTechnology($project_id, $tech);
            }
        }

        return $project_id;
    }

    public function updateProject($id, $data, $technologies = [])
    {
        $result = $this->project->updateProject($id, $data);

        if ($result) {
            // Delete existing technologies
            $this->project->deleteProjectTechnologies($id);

            // Add new technologies
            foreach ($technologies as $tech) {
                $this->project->addProjectTechnology($id, $tech);
            }
        }

        return $result;
    }

    public function deleteProject($id)
    {
        return $this->project->deleteProject($id);
    }

    public function getAllArticles()
    {
        return $this->article->getAllArticles();
    }

    public function createArticle($data)
    {
        return $this->article->createArticle($data);
    }

    public function getAllSkills()
    {
        return $this->skill->getAllSkills();
    }

    public function getSkillsByCategory()
    {
        return $this->skill->getSkillsByCategory();
    }

    public function createSkill($data)
    {
        return $this->skill->createSkill($data);
    }
}

// Contoh penggunaan untuk mengambil data ke frontend
function getPortfolioData()
{
    $admin = new AdminController();

    return [
        'personal_info' => $admin->getPersonalInfo(),
        'projects' => $admin->getAllProjects(),
        'articles' => $admin->getAllArticles(),
        'skills' => $admin->getSkillsByCategory()
    ];
}

// Fungsi helper untuk generate slug
function generateSlug($string)
{
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
    $slug = strtolower(trim($slug, '-'));
    return $slug;
}

// Fungsi helper untuk upload file
function uploadFile($file, $destination = 'uploads/')
{
    if (!isset($file['error']) || is_array($file['error'])) {
        return false;
    }

    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            return false;
        default:
            return false;
    }

    if ($file['size'] > 5000000) { // 5MB limit
        return false;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($mime, $allowed_types)) {
        return false;
    }

    $filename = uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    $filepath = $destination . $filename;

    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        return false;
    }

    return $filename;
}
