<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Diet Attachments</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .attachment-box {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 15px;
    }
    .image-box {
      position: relative;
      margin-right: 10px;
    }
    .image-box img, .image-box .pdf-icon {
      width: 60px;
      height: 60px;
      border-radius: 8px;
      object-fit: cover;
    }
    .image-box .remove-icon {
      position: absolute;
      top: -5px;
      right: -5px;
      color: red;
      background-color: white;
      border-radius: 50%;
      cursor: pointer;
      font-weight: bold;
      font-size: 16px;
      border: 1px solid red;
      width: 20px;
      height: 20px;
      text-align: center;
      line-height: 18px;
    }
    .bottom-section {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .clear-diet {
      color: red;
      cursor: pointer;
      font-weight: bold;
    }
    .edit-diet {
      color: blue;
      cursor: pointer;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="container mt-4">
    <div class="attachment-box">
      <div class="image-box">
        <img src="https://via.placeholder.com/60" alt="Image 1">
        <span class="remove-icon">&times;</span>
      </div>
      <div class="image-box">
        <img src="https://via.placeholder.com/60" alt="Image 2">
        <span class="remove-icon">&times;</span>
      </div>
      <div class="image-box">
        <div class="pdf-icon bg-light d-flex justify-content-center align-items-center" style="width: 60px; height: 60px;">
          <img src="https://via.placeholder.com/60?text=PDF" alt="PDF">
        </div>
        <span class="remove-icon">&times;</span>
      </div>
    </div>

    <div class="bottom-section">
      <div class="clear-diet">Clear Diet</div>
      <div class="file-options d-flex">
        <a href="#" class="me-3"><i class="bi bi-paperclip"></i> Attach file</a>
        <a href="#" class="edit-diet">Edit Diet</a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
