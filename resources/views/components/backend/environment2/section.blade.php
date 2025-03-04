
<div id="section_{{ $section->id }}" class="order_{{ $section->section_order }} data-view">
    <div class="accordion-header" onclick="toggleAccordion(this)">{{ $section->name }}</div>
    <div class="accordion-content">
        
             
              <x-backend.environment2.sectiondefault :surveyformid="$constructionformid" :sectionid="$section->id" :questions="$section->questions"/>
    </div>
</div>