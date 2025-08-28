# Shopee Style Photo Gallery - Admin Inventaris

## Overview
Fitur ini mengubah tampilan preview foto pada inventaris bagian detail milik admin agar sesuai dengan template preview foto seperti Shopee. Sistem dibuat fleksibel yang dapat menangani jumlah foto yang dinamis dari admin.

## Fitur Utama

### 1. Tampilan Foto Utama (Main Photo Display)
- **Ukuran**: 400px x 400px (responsive)
- **Background**: Light gray (#f8f9fa)
- **Border Radius**: 12px
- **Hover Effect**: Scale 1.05 dengan smooth transition
- **Cursor**: Zoom-in untuk UX yang lebih baik

### 2. Navigasi Foto
- **Tombol Navigasi**: Chevron left/right dengan styling modern
- **Posisi**: Absolute positioning di kiri dan kanan foto utama
- **Styling**: 
  - Background: Semi-transparent white
  - Border radius: 50%
  - Shadow: Subtle drop shadow
  - Hover: Scale 1.1 dengan enhanced shadow

### 3. Thumbnail Navigation
- **Ukuran**: 80px x 80px (responsive)
- **Layout**: Horizontal scroll dengan gap 12px
- **Styling**:
  - Border: Transparent default, teal (#20B2AA) saat active
  - Shadow: Subtle dengan hover enhancement
  - Active state: Border teal dengan glow effect
  - Hover: TranslateY(-2px) dengan enhanced shadow

### 4. Form Upload Fleksibel
- **Struktur**: 3 kolom dengan styling modern
- **Validasi**: Foto 1 wajib diisi
- **Preview**: Real-time dengan placeholder image
- **Remove Button**: Tombol hapus untuk foto 2 dan 3
- **Styling**: 
  - Background: Gradient dengan hover effect
  - Border: Dashed dengan color change pada hover
  - Input: Focus state dengan teal accent

## File yang Diupdate

### 1. Views
- `resources/views/admin/inventaris/show.blade.php` - Tampilan detail dengan Shopee style
- `resources/views/admin/inventaris/edit.blade.php` - Form edit dengan upload fleksibel
- `resources/views/admin/inventaris/create.blade.php` - Form create dengan upload fleksibel

### 2. CSS
- `public/assets/css/photo-gallery.css` - Styling untuk Shopee style gallery

## Struktur HTML

### Main Photo Container
```html
<div class="main-photo-container">
    <div id="mainPhotoDisplay" class="main-photo">
        <img src="..." alt="Foto Utama" id="mainPhotoImage">
    </div>
    
    <!-- Navigation Arrows -->
    <button class="photo-nav-btn photo-nav-prev" onclick="changeMainPhoto('prev')">
        <i class="bi bi-chevron-left"></i>
    </button>
    <button class="photo-nav-btn photo-nav-next" onclick="changeMainPhoto('next')">
        <i class="bi bi-chevron-right"></i>
    </button>
</div>
```

### Thumbnail Navigation
```html
<div class="thumbnail-navigation">
    <div class="thumbnail-item active" onclick="changeMainPhoto(0)" data-index="0">
        <img src="..." alt="Thumbnail 1">
    </div>
    <!-- More thumbnails... -->
</div>
```

### Dynamic Photo Upload
```html
<div class="dynamic-photo-upload">
    <div class="row" id="photoUploadContainer">
        <div class="col-md-4 mb-3 photo-upload-item">
            <label class="form-label fw-bold">Foto 1 <span class="text-danger">*</span></label>
            <div class="photo-upload-wrapper">
                <input type="file" name="foto1" class="form-control photo-input" required>
                <div class="photo-preview-container mt-2">
                    <img id="preview1" src="..." alt="Preview Foto 1" class="photo-preview">
                </div>
            </div>
        </div>
        <!-- More photo upload items... -->
    </div>
</div>
```

## JavaScript Functions

### 1. changeMainPhoto(direction)
```javascript
function changeMainPhoto(direction) {
    if (typeof direction === 'number') {
        currentPhotoIndex = direction;
    } else if (direction === 'next') {
        currentPhotoIndex = (currentPhotoIndex + 1) % totalPhotos;
    } else if (direction === 'prev') {
        currentPhotoIndex = (currentPhotoIndex - 1 + totalPhotos) % totalPhotos;
    }
    
    // Update main photo and thumbnail state
    updateMainPhoto();
    updateThumbnailActiveState();
}
```

### 2. previewImage(input, previewId)
```javascript
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            const previewElement = document.getElementById(previewId);
            previewElement.src = e.target.result;
            
            // Add remove button for non-required photos
            if (previewId !== 'preview1') {
                addRemoveButton(previewId, input.name);
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
```

### 3. removePhoto(previewId, inputName)
```javascript
function removePhoto(previewId, inputName) {
    // Reset preview to placeholder
    const previewElement = document.getElementById(previewId);
    previewElement.src = placeholderImageUrl;
    
    // Clear file input
    const inputElement = document.querySelector(`input[name="${inputName}"]`);
    inputElement.value = '';
    
    // Remove remove button
    removeRemoveButton(previewId);
}
```

## Responsive Design

### Desktop (≥768px)
- Main photo: 400px height
- Thumbnails: 80px x 80px
- Navigation buttons: 40px x 40px

### Tablet (576px - 767px)
- Main photo: 300px height
- Thumbnails: 70px x 70px
- Navigation buttons: 36px x 36px

### Mobile (<576px)
- Main photo: 250px height
- Thumbnails: 60px x 60px
- Navigation buttons: 32px x 32px

## Color Scheme

### Primary Colors
- **Teal**: #20B2AA (active states, borders)
- **Blue**: #007bff (primary buttons, links)
- **Gray**: #f8f9fa (backgrounds)

### Hover States
- **Navigation buttons**: Enhanced shadow and scale
- **Thumbnails**: TranslateY(-2px) with enhanced shadow
- **Photo previews**: Scale 1.02 with enhanced shadow

## Validasi Form

### Foto 1 (Wajib)
- Required attribute pada input
- JavaScript validation sebelum submit
- Alert jika tidak diisi

### Foto 2 & 3 (Opsional)
- Tidak ada required attribute
- Bisa dikosongkan
- Tombol remove untuk reset

## Keunggulan Fitur

### 1. User Experience
- **Visual Feedback**: Hover effects dan transitions
- **Intuitive Navigation**: Thumbnail click dan arrow navigation
- **Responsive Design**: Optimal di semua device

### 2. Flexibility
- **Dynamic Photo Count**: Mendukung 1-3 foto
- **Smart Layout**: Layout menyesuaikan jumlah foto
- **Easy Management**: Upload, preview, dan remove yang mudah

### 3. Performance
- **Lazy Loading**: Foto hanya load saat dibutuhkan
- **Smooth Transitions**: CSS transitions untuk animasi
- **Optimized Images**: Object-fit untuk aspect ratio yang konsisten

## Cara Penggunaan

### Untuk Admin
1. **Upload Foto**: Pilih file dari 3 field foto
2. **Preview Real-time**: Lihat preview foto secara langsung
3. **Remove Foto**: Gunakan tombol remove untuk foto 2 dan 3
4. **Submit Form**: Foto 1 wajib diisi

### Untuk Viewer
1. **Lihat Foto Utama**: Foto ditampilkan dalam container besar
2. **Navigasi**: Gunakan arrow atau thumbnail untuk ganti foto
3. **Responsive**: Tampilan optimal di semua device

## Maintenance

### CSS Updates
- Semua styling ada di `photo-gallery.css`
- Responsive breakpoints sudah diatur
- Color scheme mudah diubah

### JavaScript Updates
- Functions modular dan reusable
- Event listeners properly managed
- Error handling untuk edge cases

## Future Enhancements

### 1. Zoom Feature
- Click foto untuk zoom in/out
- Modal dengan lightbox effect

### 2. Drag & Drop
- Drag & drop untuk upload foto
- Visual feedback saat drag

### 3. Photo Cropping
- Built-in photo editor
- Crop dan resize sebelum upload

### 4. Bulk Upload
- Multiple file selection
- Progress bar untuk upload

## Troubleshooting

### Common Issues
1. **Foto tidak muncul**: Check file path dan permissions
2. **Navigation tidak work**: Check JavaScript console untuk errors
3. **Styling tidak apply**: Check CSS file path dan cache

### Debug Steps
1. Check browser console untuk JavaScript errors
2. Verify CSS file is loaded
3. Check file permissions untuk uploaded images
4. Test responsive behavior di berbagai device
