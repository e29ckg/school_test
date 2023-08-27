

Vue.createApp({
    data() {
      return {
            q:'',
            datas: '',
            //----โรงเรียน
            schools: [
                {
                    'sch_id'    : '1',
                    'sch_no'    : '1',
                    'sch_name'  : 'โรงเรียน..',
                    'award'     : '0'
                },
                {
                    'sch_id'    : '2',
                    'sch_no'    : '2',
                    'sch_name'  : 'โรงเรียน2',
                    'award'     : '0'
                }
            ],
            school: {
                    'sch_id'    : '',
                    'sch_no'    : '',
                    'sch_name'  : '',
                    'award'     : '',
                    'pic'     : '',
                    'act'     : 'insert'
            },

            //-----ผู้เข้าร่วม
            students: [{
                'std_id'    : '1',
                'sch_id'    : '1',
                'std_no'    : '',
                'std_name'  : '',
                'std_st'    : ''
            }],
            student: {
                'std_id'    : '',
                'sch_id'    : '',
                'std_no'    : '',
                'std_name'  : '',
                'std_st'    : '',
                'act'       : 'insert'
            },
            
            awards : ['','ชนะเลิศ','รองชนะเลิศ ลำดับที่ ๑','รองชนะเลิศ ลำดับที่ ๒','รองชนะเลิศ ลำดับที่ ๓','ชมเชย','รอผล'],
            std_sts : ['', 'ผู้เข้าแข่งขัน','อาจารย์'],

            user_img  : {
                uid:'',
                title:'',
                img:'',
                val:''
              },
            isLoading : false,
        }
    },
    mounted(){
        this.get_schools()
    },
    watch: {
        q(){
            this.search()
        }
      },
    
    methods: {
        get_schools(){
            this.isLoading = true;
            axios.post('./api/index/get_schools.php')
            .then(response => {
                if (response.data.status) {
                    this.schools = response.data.data
                }
            })
            .catch(function (error) {
                console.log(error);
            })
            .finally(() => {
                this.isLoading = false;
            })
        }, 

        school_cls(){
            this.school = {'sch_id': '', 'sch_no': '', 'sch_name':'', 'award': '', 'act':'insert'}
        },
        school_add(){
            this.school_cls()
            this.school.act = 'insert'
            this.$refs.modal_school_button.click()

        },        
        school_update(idx){
            this.school = this.schools[idx]
            this.school.act = 'update'
            this.$refs.modal_school_button.click()

        },        
        modal_school_close(){
            this.school_cls()
            this.school.act = 'insert'
        },
        school_save(){
            this.isLoading = true
            axios.post('./api/index/school_act.php',{school:this.school})
            .then(response => {
                const resp = response.data 

                if(resp.status){
                    this.alert('success', resp.message, 1000);
                    this.$refs.btn_sch_close.click() 
                    this.school_cls()
                    this.get_schools()
                }else{

                    this.alert('error', resp.message, 0);
                }
            })
            .catch(function (error) {
                console.log(error);
            })
            .finally(() => {
                this.isLoading = false;
            })        
        },
        school_del(){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
              }).then((result) => {
                if (result.isConfirmed) {
                    this.isLoading = true
                    // this.school = this.schools[idx_school]
                    this.school.act = 'del'
                    axios.post('./api/index/school_act.php',{
                        school:this.school
                    })
                    .then(response => {
                        const resp = response.data 
        
                        if(resp.status){
                            this.alert('success', resp.message, 1000);
                            this.get_schools() 
                            this.$refs.btn_sch_close.click() 
                        }else{
        
                            this.alert('error', resp.message, 0);
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => {
                        this.isLoading = false;
                    })
                }
            })
        },

        student_cls(){
            this.student ={'std_id' : '', 'sch_id' : '', 'std_no'  : '', 'std_name'  : '', 'std_st' : '', 'act' : 'insert'}
        },
        student_show(idx_school){
            this.student_cls()
            this.school = this.schools[idx_school],
            this.student.act = 'get_all'
            this.student_all()
            this.$refs.modal_student_button.click()
            
        },
        modal_std_close(){
            this.get_schools()
        },
        student_update(idx_student){
            this.student_cls()
            // this.student = this.students[idx_student],
            // this.student.act = 'update'    
            this.isLoading = true
            axios.post('./api/index/student_act.php',{
                school:this.school,
                student:this.students[idx_student],
            })
            .then(response => {
                const resp = response.data 

                if(resp.status){
                    this.students[idx_student].act = ''   
                    // this.alert('success', resp.message, 1000);
                    // this.student_all() 
                    // this.get_schools()
                }else{

                    this.alert('error', resp.message, 0);
                }
            })
            .catch(function (error) {
                console.log(error);
            })
            .finally(() => {
                this.isLoading = false;
            })       
        },
        student_all(){
            this.isLoading = true;
            this.student_cls()
            this.student.act = 'get_all'
            axios.post('./api/index/student_act.php',{
                school:this.school,
                student:this.student,
            })
            .then(response => {
                this.students = response.data.data
                
                this.student.act = 'insert'
            })
            .catch(function (error) {
                console.log(error);
            })
            .finally(() => {
                this.isLoading = false;
            })
        },
        
        student_save(){
            this.isLoading = true
            axios.post('./api/index/student_act.php',{
                school:this.school,
                student:this.student
            })
            .then(response => {
                const resp = response.data 

                if(resp.status){
                    this.alert('success', resp.message, 1000);
                    this.student_all() 
                    this.get_schools()
                }else{

                    this.alert('error', resp.message, 0);
                }
            })
            .catch(function (error) {
                console.log(error);
            })
            .finally(() => {
                this.isLoading = false;
            })
        
        },
        student_del(idx_student){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
              }).then((result) => {
                if (result.isConfirmed) {
                    this.isLoading = true
                    this.student = this.students[idx_student]
                    this.student.act = 'del'
                    axios.post('./api/index/student_act.php',{
                        school:this.school,
                        student:this.student
                    })
                    .then(response => {
                        const resp = response.data 
        
                        if(resp.status){
                            this.alert('success', resp.message, 1000);
                            this.student_all() 
                            this.get_schools()
                        }else{
        
                            this.alert('error', resp.message, 0);
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => {
                        this.isLoading = false;
                    })
                }
            })
        },

        test(){
            this.alert('error', 'resp.message', 0);
        },

        
       print_std(idx_std){
        this.isLoading = true
        
        axios.post('./service/mpdf/api/index/print.php',{
            school:this.school,
            student:this.students[idx_std]
        }) 
        .then(response => {
            url = response.data.url
            console.log(url)
            window.open(url,'_blank')
        })
        .catch(function (error) {
            console.log(error);
        })
        .finally(() => {
            this.isLoading = false;
        })
  
      }, 
       print_std_word(idx_std){
        this.isLoading = true
        
        axios.post('./service/word/print_word.php',{
            school:this.school,
            student:this.students[idx_std]
        }) 
        .then(response => {
            const resp = response.data 
        
            if(resp.status){
                this.alert('success', resp.message, 1000);
                url = response.data.url
                console.log(url)
                window.open(url,'_blank')
            }else{
                this.alert('error', resp.message, 0);
            }
        })
        .catch(function (error) {
            console.log(error);
        })
        .finally(() => {
            this.isLoading = false;
        })  
      }, 

      b_user_img(index){
        this.school_cls()
        this.school = this.schools[index];   
        this.user_img.uid = this.schools[index].sch_id;   
        this.user_img.img = this.schools[index].pic;   
        this.$refs.show_user_img.click()     
      },
      onChangeInput(event){
        this.onUpload()
      },
      onUpload(){
        // console.log(this.$refs.myFiles.files[0].name);
        var image = this.$refs.myFiles.files
        if (image.length > 0) {
          if(image[0].type == 'image/jpeg' || image[0].type =='image/png') {
            var formData = new FormData();
            // var imagefile = document.querySelector('#file');
            // var imagefile = document.querySelector('#file');
            formData.append("sendimage", image[0]);
            formData.append("uid", this.user_img.uid);
            axios.post('./api/index/upload_img.php', 
              formData, 
              {headers:{'Content-Type': 'multipart/form-data'}
            })
              .then(response => {
                  if (response.data.status) {
                    swal.fire({
                      icon: 'success',
                      title: response.data.message,
                      showConfirmButton: true,
                      timer: 1500
                    });
                    this.get_schools();
                    this.user_img.img = response.data.img;
                    this.$refs.close_user_img.click()
  
                  }else {
                      swal.fire({
                          icon: 'error',
                          title: response.data.message,
                          showConfirmButton: true,
                          timer: 1500
                      });
                  }
              })
          } else{
            swal.fire({
              icon: 'error',
              title: "ไฟล์ที่อัพโหลดต้องเป็นไฟล์ jpeg หรือ png เท่านั้น",
              showConfirmButton: true,
              timer: 1500
            });
          }
        }  
      },
      


     
      alert(icon,message,timer=0){
        swal.fire({
          icon: icon,
          title: message,
        //   showConfirmButton: false,
          timer: timer
        });
      },
    },
  
  }).mount('#index')

  