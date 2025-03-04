<div id="section_{{ $section->id }}" class="order_{{ $section->section_order }} data-view">
    <div class="accordion-header" onclick="toggleAccordion(this)">{{ $section->name }}</div>
    <div class="accordion-content">
              @if($section->id == 47)
              <x-frontend.survey.sectionuploadphotodocuments :surveyformid="$surveyformid" :sectionid="$section->id" :questions="$section->questions"/>
              @elseif($section->id == 125 || $section->id == 124 || $section->id == 123 || $section->id == 117)
              <x-frontend.survey.sectionfunctionallimitation :surveyformid="$surveyformid" :sectionid="$section->id" :questions="$section->questions"/>
              @else
              <x-frontend.survey.sectiondefault :surveyformid="$surveyformid" :sectionid="$section->id" :questions="$section->questions"/>
              @endif
    </div>
</div>