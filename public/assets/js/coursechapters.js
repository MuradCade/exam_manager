$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content')
    }
});

$(document).ready(function () {

    const url = window.location.href; // replace with route if possible

    // create course chapter
    $('#chapterform').on('submit', function (e) {
        e.preventDefault();

        // remove previous errors & messages immediately
        $('.text-danger').remove();
        $('#msg').html('');

        $.ajax({
            url: url,
            method: 'POST',
            data: $(this).serialize(),

            success: function (response) {


            if(response.success){
                toastr.success(response.success)
                 displayallchapters();
                 $('#chaptermodal').modal('hide');
                 // optional: reset form
                    $('#chapterform')[0].reset();

            }else{

                console.log(response);
            }
             },

            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;

                    Object.keys(errors).forEach(function (key) {
                        const input = $('[name="' + key + '"]');
                        input.after(
                            `<small class="text-danger">${errors[key][0]}</small>`
                        );
                    });
                }
            }
        });
    });

// display all course chapters
    function displayallchapters(){
            $('#allchapters').html('Loading...')
        $.ajax({
            url:url+'/displaychapters',
            method: 'GET',
            dataType:'json',
            success:function(response){
                $('#allchapters').html('')

               if (response.length > 0) {

            response.forEach(chapter => {
                
                const headingId = `chapterHeading${chapter.id}`;
                const collapseId = `chapterCollapse${chapter.id}`;

                $('#allchapters').append(`
                    <div class="accordion-item">
                        
                        <h2 class="accordion-header" id="${headingId}">
                        <button
                                class="accordion-button collapsed fw-bold"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#${collapseId}"
                                aria-expanded="false"
                                aria-controls="${collapseId}">
                                ${chapter.chapter_title}
                                
                            </button>
                            </h2>
                            <!-- chapter actions buttons starts here-->
                            <div class='p-2'>
                                <button class='update-chapter' data-id='${[chapter.id]}' style='background:transparent !important; border:none !important;'> <i class='fas fa-edit' class='me-2' style='color:#3b71ca;font-size:12px;'></i> </button>

                                <button class='delete-chapter' data-id='${[chapter.id]}' style='background:transparent !important; border:none !important;'> <i class='fas fa-trash' class='me-2' style='color:red;font-size:12px;'></i> </button>
                            </div>
                            <!-- chapter actions buttons ends here-->

                        <div
                            id="${collapseId}"
                            class="accordion-collapse collapse"
                            aria-labelledby="${headingId}"
                            data-mdb-parent="#chaptersAccordion">

                            <div class="accordion-body">
                                <button class="btn btn-secondary btn-sm fw-bold text-capitalize create-lesson-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#lessonmodal"
                                data-id="${chapter.id}">Create Lesson</button>

                                <div class="chapterlessons" id="chapterlessons-${chapter.id}"></div>
                            </div>

                        </div>

                    </div>
                        `);
                        // Fetch lessons immediately for this chapter
                        $.ajax({
                            url: url+`/${chapter.id}/displaylessons`,  // your route
                            method: 'GET',
                            data: { 'chapterid': chapter.id },
                            success: function(lessons) {
                                let $lessonDiv = $(`#chapterlessons-${chapter.id}`);
                                $lessonDiv.html(''); // clear any old content

                                if (lessons.length > 0) {
                                    lessons.forEach(lesson => {
                                        $lessonDiv.append(`
                                            <div class="lesson-item p-2 mt-2 mb-2 border">
                                                <div>
                                                ${lesson.lesson_title}
                                                </div>
                                                <button class='update-lesson' data-id='${lesson.id}' style='background:transparent !important; border:none !important;'><i class='fas fa-edit' class='me-2' style='color:#3b71ca;'></i></button>
                                               <button class='delete-lesson' data-id='${[lesson.id,lesson.chapter_id]}' style='background:transparent !important; border:none !important;'> <i class='fas fa-trash' class='me-2' style='color:red'></i> </button>
                                                <div>
                                                </div>
                                            </div>
                                        `);
                                    });
                                } else {
                                    $lessonDiv.html('<small>No Lesson Found.</small>');
                                }
                            },
                            error: function(xhr) {
                                console.error(xhr.responseText);
                            }
                        });
                    });
                }else{
                    $('#allchapters').html('No Chapters Found')
                }

                
            },
            error:function(xhr,status,error){
                console.log(error);
                
            }

        })
    }

    displayallchapters();
    

//populate chapterid inside the lesson modal so i can create new lesson
$(document).on('click', '.create-lesson-btn', function() {
    let chapterId = $(this).data('id');

    // pass the courseid to the lessonmodal
        $('#lessonform').data('chapter_id', chapterId);
    // passing chapterid to the input hiddem field 
   $('#lessonform input[name="chapterid"]').val(chapterId);
});

// create lessons
    $('#lessonform').submit(function(event){
        event.preventDefault();
    
    // get passed chapterid
    let chapterid = $(this).data('chapter_id');

        
    $.ajax({
        url:url+`/${chapterid}/createlesson`,
        method:'POST',
        data:$(this).serialize(),
        success:function(response){
            console.log(response);
            
            if (response.success) {
                toastr.success(response.success)
                displayallchapters();

                    // optional: reset form
                    $('#lessonmodal').modal('hide');
                    $('#lessonform')[0].reset();
                }
        },
         error: function (xhr,status,error) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;

                    Object.keys(errors).forEach(function (key) {
                        const input = $('[name="' + key + '"]');
                        input.after(
                            `<small class="text-danger">${errors[key][0]}</small>`
                        );
                    });
                }
            }
    })
    
    
        
    })



   // display single lesson data inside update lesson form
   $(document).on('click','.update-lesson',function(){
        let lessonid = $(this).data('id')

        let courseid = document.getElementById('courseid');
        let chapterid = document.getElementById('chapterid');
        let lesson_title = document.getElementById('lesson_title');
        let lesson_description = document.getElementById('lesson_description');
        let lesson_video = document.getElementById('lesson_video');
        let lessonidinput = document.getElementById('lessonid');
        $('#updatelessonmodel').modal('show')
        
        
        $.ajax({
            url:url+`/${courseid.value}/displaysinglelessons`,
            method:'GET',
            data:{'lessonid':lessonid},
            success:function(response){
                
                $('#updatelessonform').data('id',response.chapter_id)
                chapterid.value = response.chapter_id;
                lessonidinput.value = lessonid;
                lesson_title.value = response.lesson_title;
                lesson_description.value = response.lesson_description;
                lesson_video.value = response.video_url;
                // console.log(response);
                
                if(response.preview_enabled == 0){

                    $('#lesson_preview').html(`
                        <option value='0'selected>No</option>
                        <option value='1'>Yes</option>
                    `);
                    
                  
                }
                 else if(response.preview_enabled == 1){
                    $('#lesson_preview').html(`
                        <option value='1'selected>Yes</option>
                        <option value='0'>No</option>
                    `);
                }
            },
            error:function(xhr,status,error){
                console.log(error);
                
            }
        })
        
   })


   // update single lesson data information
   $('#updatelessonform').submit(function(event){
        event.preventDefault();

        let chapterid = $(this).data('id');
        

        $.ajax({
            url:url+`/${chapterid}/update`,
            method:'POST',
            data:$(this).serialize(),
            success:function(response){
                if(response.success){
                    
                     toastr.success(response.success)
                 displayallchapters();
                    displayallchapters();
                    $('#updatelessonmodel').modal('hide')
                    // optional: reset form
                    $('#updatelessonform')[0].reset();
                }else{

                    console.log(response);
                }

                
            },
            error:function(xhr,status,error){
               if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;

                    Object.keys(errors).forEach(function (key) {
                        const input = $('[name="' + key + '"]');
                        input.after(
                            `<small class="text-danger">${errors[key][0]}</small>`
                        );
                    });
                }
            }
        })
        
   })



// delete lesson 

$(document).on('click','.delete-lesson',function(){
    let data = $(this).data('id');
    let splitdata = data.split(',');
    let lessonid = splitdata[0];
    let chapterid = splitdata[1];
    // let splitdata = data.split(',');

    $.ajax({
        url:url+`/${chapterid}/delete`,
        method:'GET',
        data:{'lessonid':lessonid},
        success:function(response){
            
            if(response.success){
                toastr.success(response.success)
                 displayallchapters();

            }else{
            console.log(response);
            }
        },
        error:function(xhr,status,error){
            console.log(error);
            
        }
    })
    
})





//===== display  chapterinformation on update form

$(document).on('click','.update-chapter',function(){
    // $('#chaptermodal').data()
    let chapterid = $(this).data('id');
   $('#updatechapterform input[name="chapterid"]').val(chapterid);


    // pass chapterid to the chapterform
    $('#updatechapterform').data('id')

    $('#updatechaptermodal').modal('show');


    let chapter_title = document.getElementById('chapter_title');
    let chapter_order = document.getElementById('chapter_order');
        
    $.ajax({
        url:url+'/display_singlechapterdata',
        method:'GET',
        dataType:'json',
        data:{'chapterid':chapterid},
        success:function(response){
            chapter_title.value = response.chapter_title;
            chapter_order.value = response.chapter_order;
        },
        error:function(xhr,status,error){
            console.log(error);
        }
    })


    
})

// update chapter information
$('#updatechapterform').submit(function(event){
    event.preventDefault();

    $.ajax({
        url:url+'/updatechapterdata',
        method:'GET',
        dataType:'json',
        data:$(this).serialize(),
        success:function(response){
            if(response.success){
                toastr.success(response.success)
                $('#updatechaptermodal').modal('hide');
                 displayallchapters();

            }else{
            console.log(response);
            }
        },
        error:function(xhr,status,error){
            if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;

                    Object.keys(errors).forEach(function (key) {
                        const input = $('[name="' + key + '"]');
                        input.after(
                            `<small class="text-danger">${errors[key][0]}</small>`
                        );
                    });
                }
        }
    })


    
})


// delete single chapter

$(document).on('click','.delete-chapter',function(){
    let chapterid = $(this).data('id');

    $.ajax({
        url:url+'/deletechapter',
        method:'GET',
        data:{'chapterid':chapterid},
        success:function(response){
            if(response.success){
                toastr.success(response.success)
                 displayallchapters();

            }else{
            console.log(response);
            }
        },
        error:function(xhr,status,error){
            console.log(error);
        }
    })
    
})
    
});
