<form method="post" action="/festival/tickets/register.php" id="form" accept-charset="UTF-8" v-scope v-cloak>
    <div class="required" title="Champ obligatoire">
        <input type="text" class="required" name="name" placeholder="Prénom, nom" required minlength="2" autofocus>
    </div>

    <p>Billets</p>

    <label class="field" v-for="(ticket, index) in tickets">
        <input type="checkbox" :name="`ticket${index+1}`" v-model="ticket.checked">
        <span class="name">{{ ticket.name }}</span>
        <span class="price" v-if="hasDiscount(ticket)">+ CHF {{ ticket.price + ticketDiscount }}</span>
        <span class="price" v-else>CHF {{ ticket.price }}</span>
    </label>

    <template v-if="hasSomeTicket()">
        <p>Repas</p>
        <p class="disclaimer">🌿 Tous les repas sont végétariens&nbsp;! 🌿</p>

        <div class="meals">
            <label class="field" v-for="(meal, index) in possibleMeals()">
                <input type="checkbox" :name="`meal${index+1}`" v-model="meal.checked">
                <span class="name meal-info">
                    <span>{{ meal.date }}</span>
                    <span>{{ meal.name }}</span>
                </span>
                <span class="price">+ CHF {{ meal.price }}</span>
            </label>
        </div>

        <p></p>
        <textarea name="message" rows="5" placeholder="Petit message pour les organisateurs"></textarea>

        <label>
            <input type="checkbox" name="conditionsAccepted">
            J'ai lu et j'accepte les
            <a @click="openDialog">conditions de participation</a>.
        </label>

        <p>Prix total&nbsp;: CHF {{ totalPrice() }}</p>

        <input type="hidden" name="conditionsRead" v-model="conditionsRead">

        <button id="button">
            <span id="buttonText">S'inscrire</span>
        </button>
    </template>

    <dialog v-if="showDialog" open>
        <video id="video" autoplay loop @click="video.play()">
            <source src="/festival/medias/conditions.mp4" type="video/mp4">
        </video>
    </dialog>
</form>

<script type="module">
    import { createApp } from "https://unpkg.com/petite-vue@0.4.1?module"

    const ticketPrice = <?= $festival["ticket_price"] ?>

    const mealPrice = <?= $festival["meal_price"] ?>

    const ticketDiscount = <?= $festival["ticket_discount"] ?>

    const mealNames = ["<?= $festival["meal1"] ?>", "<?= $festival["meal2"] ?>", "<?= $festival["meal3"] ?>", "<?= $festival["meal4"] ?>"]

    createApp({
        tickets: [
            { name: "Vendredi 19 juillet", price: ticketPrice, checked: false },
            { name: "Samedi 20 juillet", price: ticketPrice, checked: false },
        ],
        meals: [
            { name: mealNames[0], date: "Vendredi 19 – soir", price: mealPrice, checked: false, tickets: [0] },
            { name: mealNames[1], date: "Samedi 20 – midi", price: mealPrice, checked: false, tickets: [0, 1] },
            { name: mealNames[2], date: "Samedi 20 – soir", price: mealPrice, checked: false, tickets: [1] },
            { name: mealNames[3], date: "Dimanche 21 – midi", price: mealPrice, checked: false, tickets: [1] },
        ],
        ticketDiscount,
        conditionsRead: false,
        showDialog: <?= isset($showDialog) ? "true" : "false" ?>,
        hasSomeTicket() {
            return this.tickets.some(t => t.checked)
        },
        hasAllTickets() {
            return this.tickets.every(t => t.checked)
        },
        hasDiscount(ticket) {
            return !ticket.checked && this.hasSomeTicket() ||
                this.hasAllTickets() && this.tickets.indexOf(ticket) > 0
        },
        possibleMeals() {
            return this.meals.filter(meal => meal.tickets.some(index => this.tickets[index].checked))
        },
        totalPrice() {
            return [...this.tickets, ...this.meals]
                    .filter(item => item.checked)
                    .reduce((sum, item) => sum + item.price, 0) +
                (this.hasAllTickets() ? ticketDiscount : 0)
        },
        openDialog(event) {
            event.preventDefault()
            this.showDialog = true
            this.conditionsRead = true
            document.body.classList.add("no-scroll")
            history.pushState({}, undefined, "/festival/conditions")
            setTimeout(() => document.getElementById("video").play(), 100)

            addEventListener("popstate", event => {
                event.preventDefault()
                this.showDialog = false
                document.body.classList.remove("no-scroll")
            })
        },
    }).mount()
</script>
