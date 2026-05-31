
<template>

    <div class="row g-2">
        <!--            <div class="col-12">
                        <CardSlider :items="cards">
                            <template #card="{ card }">
                                <div class="card-inner">
                                    <img :src="card.image" class="card-img" />
                                    <div class="title">{{ card.title }}</div>
                                </div>
                            </template>
                        </CardSlider>
                    </div>-->
        <div class="col-md-4">
            <div class="card h-100 menu-card rounded-4"
                 @click="goTo('IncomingReportPage')">
                <div class="card-body text-center px-2 py-3">
                    <i class="fa-solid fa-triangle-exclamation fa-3x mb-3 text-primary"></i>

                    <h5 class="card-title">
                        Жалоба на проблему
                    </h5>

                    <p class="card-text text-muted">
                        Сообщить о возникшей проблеме
                    </p>
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="card h-100 menu-card rounded-4"
                 @click="goTo('EventRequestPage')">
                <div class="card-body text-center px-2 py-3">
                    <i class="fa-solid fa-calendar-days fa-3x mb-3 text-success"></i>

                    <h5 class="card-title">
                        Запрос на мероприятие
                    </h5>

                    <p class="card-text text-muted">
                        Подать заявку на проведение мероприятия
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 menu-card rounded-4"
                 @click="goTo('VolunteerPage')">
                <div class="card-body text-center px-2 py-3">
                    <i class="fa-solid fa-hand-holding-heart fa-3x mb-3 text-danger"></i>

                    <h5 class="card-title">
                        Стать волонтером
                    </h5>

                    <p class="card-text text-muted">
                        Присоединиться к волонтерской команде
                    </p>
                </div>
            </div>
        </div>
    </div>





</template>
<script>
export default {
        data(){
          return {
              tab:"main",
              cards: [
                  { title: 'Первая', image: '/screenshots/grajdanin-mobile.png' },
                  { title: 'Вторая', image: '/images/2.jpg' },
                  { title: 'Третья', image: '/images/3.jpg' },
                  { title: 'Четвёртая', image: '/images/4.jpg' }
              ]
          }
        },
        methods:{
            goTo(routeName) {
                const el = document.getElementById('sidebar-menu')
                const sidebar = bootstrap.Offcanvas.getOrCreateInstance(el)
                sidebar.hide()

                this.$router.push({name: routeName})
            },
            onIncomingSuccess() {
                this.tab = 'main'
            },
            hideModal(modalId){
                const modalEl = document.getElementById(modalId)
                const modalInstance = bootstrap.Modal.getInstance(modalEl)
                modalInstance.hide()
            }
        }
}
</script>
<style>
.menu-card {
    cursor: pointer;
    border-radius: 16px;
    transition: all .2s ease;
    border: 1px solid #e9ecef;
}

.menu-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0,0,0,.08);
    border-color: var(--bs-primary);
}
</style>
