<div id="section_{{ $section->id }}" class="order_{{ $section->section_order }} data-view">
    <div class="accordion-header" onclick="toggleAccordion(this)">{{ $section->name }}</div>
    <div class="accordion-content">
              @if($section->id == 144)
              @elseif($section->id == 174)
              @else
              @endif
              <x-backend.mne.sectiondefault :mneid="$mneid" :sectionid="$section->id" :questions="$section->questions"/>
    </div>
</div>