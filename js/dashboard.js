

Vue.createApp({
    data() {
      return {
            q:'',
            datas: '',  
            quizs:[],          
            quiz:'',
            q_name :'Admin',          
            q_id: 10,
            timer: null,
            order_by: 'คะแนน',  
            
            url_video:'video/ct-60s.mp4',
            school: {
              'sch_id'    : '',
              'sch_no'    : '',
              'sch_name'  : 'xxx',
              'award'     : '',
              'pic'     : '',
              'act'     : 'update'
            },
            awards : ['','ชนะเลิศ','รองชนะเลิศ ลำดับที่ ๑','รองชนะเลิศ ลำดับที่ ๒','รองชนะเลิศ ลำดับที่ ๓','ชมเชย','รอผล'],

            isLoading : false,
        }
    },
    mounted(){
        this.get_schools()
        this.get_quiz()
    },
    watch: {
        q(){
            this.search()
        }
      },
    
    methods: {
        get_schools(){
            clearInterval(this.timer)
            axios.post('./api/dashboard/get_schools.php',{
                q_id:this.q_id,
                order_by:this.order_by
            })
            .then(response => {
                this.datas = response.data.data
                this.q_name = response.data.quiz.q_name
                
                this.timer = setInterval(() => {
					this.get_schools()
				}, 2000)
            })
            .catch(function (error) {
                console.log(error);
            })
        },
        get_quiz(){
            axios.post('./api/dashboard/get_quizs.php')
            .then(response => {
                this.quizs = response.data.data
                this.q_id = this.quizs[0].q_id
                
            })
            .catch(function (error) {
                console.log(error);
            })
        },
        order(s){
          this.order_by = s
          this.get_schools()

        },
        school_cls(){
          this.school = {'sch_id': '', 'sch_no': '', 'sch_name':'', 'award': '', 'act':'update'}
      },
        show_modal_award(index){
          this.school_cls()
          this.school = this.datas[index]
          this.school.act = 'update'
          this.$refs.modal_button_award.click() 
        },
        modal_school_close(){
          this.school_cls()
          this.school.act = 'update'
      },
      school_save(){
          this.isLoading = true
          axios.post('./api/index/school_act.php',{school:this.school})
          .then(response => {
              const resp = response.data 

              if(resp.status){
                  this.alert('success', resp.message, 1000);
                  this.$refs.modal_button_award_close.click() 
                  this.school_cls()
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

     
      alert(icon,message,timer=0){
        swal.fire({
          icon: icon,
          title: message,
        //   showConfirmButton: false,
          timer: timer
        });
      },
    },
  
  }).mount('#dashboard')

  