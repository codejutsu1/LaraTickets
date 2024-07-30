<x-filament-panels::page>
<style>
        .header-div{
            position: relative; 
            width: 100%; 
        }

        .header-button-div{
            position: fixed; 
            width: 70%; 
            /* background: black; */
        }
        
        .button-div{
            display: flex;
            justify-content: end;
        }
        .post {
            padding-top: 20px;
        }
    </style>

    <div class="header-div">
        <div class="header-button-div">
            <div class="button-div">
                {{ $this->editAction }}
            </div>
        </div>
    </div>

    <div class="post">
        {{ $this->ticketInfolist }}
    </div>

    <div class="post">
        {{ $this->commentInfolist }}
    </div> 

    <div>
        <x-filament-actions::modals />
    </div>
</x-filament-panels::page>