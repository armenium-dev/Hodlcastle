<canvas id="chartReport" class="w-full h-full"
        data-labels="{{ $campaigns_for_table->sortBy('created_at')->pluck('name')->implode(",") }}"
        data-short-labels="{{ $labels->implode(",") }}"
        data-data-sents="[{{ $campaigns_for_table->sortBy('created_at')->pluck('sentsCount')->implode(",") }}]"
        data-data-opens="[{{ $campaigns_for_table->sortBy('created_at')->pluck('opensCount')->implode(",") }}]"
        data-data-fakeauth="[{{ $campaigns_for_table->sortBy('created_at')->pluck('fake_auth')->implode(",") }}]"
        data-data-clicks="[{{ $campaigns_for_table->sortBy('created_at')->pluck('clicksCount')->implode(",") }}]"
        data-data-reports="[{{ $campaigns_for_table->sortBy('created_at')->pluck('reportsCount')->implode(",") }}]"
        data-data-attachments="[{{ $campaigns_for_table->sortBy('created_at')->pluck('attachmentsCount')->implode(",") }}]"
        data-data-smishs="[{{ $campaigns_for_table->sortBy('created_at')->pluck('smishsCount')->implode(",") }}]">
</canvas>
