

Vue.createApp({
    data() {
      return {
            q:'',
            datas: '',
            //----โรงเรียน
            schools : [],
            quizs: [],
            quiz: {
                    'q_id'    : '',
                    'q_name'    : '',
                    'q_num'  : '',
                    'act'     : 'insert'
            },
            quiz_ansers:[],
            quiz_anser:{
                'qa_id':'',
                'q_id':'',
                'q_no':'',
                'sch_id':'',
                'act':'insert'
            },

            q_id:'',
            q_no:'',

                       

            isLoading : false,
        }
    },
    mounted(){
        this.get_quizs()
    },
    watch: {
        q(){
            this.search()
        }
      },
    
    methods: {
        get_schools(){
            this.isLoading = true;
            axios.post('./api/quiz/get_schools.php',{q_id:this.q_id, q_no:this.q_no})
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

        get_quizs(){
            this.isLoading = true;
            axios.post('./api/quiz/get_quizs.php')
            .then(response => {
                if (response.data.status) {
                    this.quizs = response.data.data
                }
            })
            .catch(function (error) {
                console.log(error);
            })
            .finally(() => {
                this.isLoading = false;
            })
        }, 

        quiz_cls(){
            this.quiz = {'q_id': '', 'q_num': '', 'q_name':'', 'act':'insert'}
        },
        quiz_add(){
            this.quiz_cls()
            this.quiz.act = 'insert'
            this.$refs.modal_quiz_button.click()

        },        
        quiz_update(idx){
            this.quiz = this.quizs[idx]
            this.quiz.act = 'update'
            this.$refs.modal_quiz_button.click()

        },        
        modal_quiz_close(){
            this.quiz_cls()
            this.quiz.act = 'insert'
        },
        quiz_save(){
            this.isLoading = true
            axios.post('./api/quiz/quiz_act.php',{quiz:this.quiz})
            .then(response => {
                const resp = response.data 

                if(resp.status){
                    this.alert('success', resp.message, 1000);
                    this.$refs.btn_quiz_close.click() 
                    this.quiz_cls()
                    this.get_quizs()
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
        quiz_del(idx_quiz){
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
                    this.quiz = this.quizs[idx_quiz]
                    this.quiz.act = 'del'
                    axios.post('./api/quiz/quiz_act.php',{
                        quiz:this.quiz
                    })
                    .then(response => {
                        const resp = response.data 
        
                        if(resp.status){
                            this.alert('success', resp.message, 1000);
                            this.get_quizs() 
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


        quiz_anser_cls(){
            this.quiz_anser = {
                'qa_id':'',
                'q_id':'',
                'q_no':'',
                'sch_id':'',
                'ck':0,
                'act':'insert'
            }
        },
        quiz_a_show(no,q_id){
            this.q_id = q_id
            this.q_no = no
            this.get_schools()
            this.$refs.modal_quiz_a_button.click() 
        },
        modal_quiz_a_close(){
            this.quiz_anser_cls()
            this.q_id = ''
            this.q_no = ''
            this.$refs.modal_quiz_a_close.click() 
        },
        quiz_school_ck(idx_sch){
            ck = this.schools[idx_sch].ck
            if(ck == 1){
                //del
                this.quiz_anser.qa_id = this.schools[idx_sch].qa_id
                this.quiz_anser.act = 'del'

                axios.post('./api/quiz/quiz_anser_act.php',{
                    quiz_anser:this.quiz_anser
                })
                .then(response => {
                    const resp = response.data 
                    if(resp.status){
                        // this.alert('success', resp.message, 1000);
                        this.get_schools() 
                    }else{    
                        this.alert('error', resp.message, 0);
                    }
                })
                .catch(function (error) {
                    console.log(error);
                })

            }
            if(ck == 0){
                //insert
                this.quiz_anser.sch_id = this.schools[idx_sch].sch_id                
                this.quiz_anser.q_id = this.q_id
                this.quiz_anser.q_no = this.q_no
                this.quiz_anser.act = 'insert'

                axios.post('./api/quiz/quiz_anser_act.php',{
                    quiz_anser:this.quiz_anser
                })
                .then(response => {
                    const resp = response.data 
                    if(resp.status){
                        // this.alert('success', resp.message, 1000);
                        this.get_schools() 
                    }else{    
                        this.alert('error', resp.message, 0);
                    }
                })
                .catch(function (error) {
                    console.log(error);
                })


            }
        },

        test(){
            this.alert('error', 'resp.message', 0);
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
  
  }).mount('#quiz')

  