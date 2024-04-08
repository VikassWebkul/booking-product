<div class="grid grid-cols-1 gap-6">
    <div class="flex gap-3">
        <span class="icon-calendar text-2xl"></span>

        <div class="grid grid-cols-1 gap-1.5 text-sm font-medium">
            <p class="text-[#6E6E6E]">
                @lang('booking::app.shop.products.view.types.booking.slot-duration') :
            </p>

            <div>
                @lang('booking::app.shop.products.view.types.booking.slot-duration-in-minutes', ['minutes' => $bookingProduct->table_slot->duration])
            </div>
        </div>
    </div>

    @inject ('bookingSlotHelper', 'Webkul\BookingProduct\Helpers\TableSlot')

    <div class="flex gap-3">
        <span class="icon-calendar text-2xl"></span>

        <div class="grid grid-cols-1 gap-4">
            <div class="grid grid-cols-1 gap-1.5 text-sm font-medium">
                <p class="text-[#6E6E6E]">
                    @lang('booking::app.shop.products.view.types.booking.table.today-availability')
                </p>
    
                <span>
                    {!! $bookingSlotHelper->getTodaySlotsHtml($bookingProduct) !!}
                </span>
            </div>

            <!-- Toggler Vue Component -->
            <v-toggler></v-toggler>
        </div>
    </div>

    @include ('booking::shop.products.view.types.booking.slots', [
        'bookingProduct' => $bookingProduct, 
        'title' => trans('booking::app.shop.products.view.types.booking.table.book-a-table')
    ])

    <!-- Notes -->
    <x-shop::form.control-group class="w-full !mb-0">
        <x-shop::form.control-group.label class="required">
            @lang('booking::app.shop.products.view.types.booking.table.special-notes')
        </x-shop::form.control-group.label>

        <x-shop::form.control-group.control
            type="textarea"
            class="!mb-0"
            name="booking[note]"
            rules="required"
            :label="trans('booking::app.shop.products.view.types.booking.table.special-notes')"
            :placeholder="trans('booking::app.shop.products.view.types.booking.table.special-notes')"
        />

        <x-shop::form.control-group.error control-name="booking[note]" />
    </x-shop::form.control-group>
</div>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-toggler-template"
    >
        <div class="grid gap-3 w-max select-none">
            <!-- Details Toggler -->
            <p
                class="flex gap-x-[15px] items-center text-blue-600 text-sm font-medium cursor-pointer"
                @click="showDaysAvailability = ! showDaysAvailability"
            >
                @lang('booking::app.shop.products.view.types.booking.table.slots-for-all-days')

                <span
                    class="text-xl font-bold"
                    :class="{'icon-arrow-up': showDaysAvailability, 'icon-arrow-down': ! showDaysAvailability}"
                >
                </span>
            </p>

            <!-- Option Details -->
            <div
                class="grid grid-cols-2 gap-3"
                v-show="showDaysAvailability"
                v-for="day in days"
            >
                <p
                    class="text-sm text-gray font-medium"
                    v-text="day.name"
                >
                </p>

                <p class="text-sm text-gray-600">
                    <template v-if="day.slots && day.slots?.length">
                        <div v-for="slot in day.slots">
                            @{{ slot.from }} - @{{ slot.to }}
                        </div>
                    </template>

                    <div v-else>
                        @lang('booking::app.shop.products.view.types.booking.table.closed')
                    </div>
                </p>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-toggler', {
            template: '#v-toggler-template',

            data() {
                return{
                    showDaysAvailability: '',

                    days: @json($bookingSlotHelper->getWeekSlotDurations($bookingProduct)),
                }
            },
        })
    </script>
@endpushOnce