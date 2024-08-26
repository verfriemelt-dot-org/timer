# timetracking tool

```
$ tt help

  add                  used to add non-work entries
  balance              prints out the current overtime balance
  export:csv           exports the current year as a csv
  export:text:month    prints the given month
  export:text:year     prints the current year
  help                 prints out helpful information about commands
  holiday:add          adds a new holiday
  holiday:list         prints all know holidays
  hours:add            adds hours definitions
  hours:show           dumps out hours definitions
  print:day            the default action; prints the time balance of the current day
  reset                discards the current worktime entry
  toggle               start and stops new entries and adds them to the repository

```

### examples

**init**
```
$ ./tt
entry repo not initialized ... created!
hours repo not initialized
working hours starting from monday (like "8 8 8 5.5 5.5 0 0" - needs to be 7 values)?
8 8 8 8 8 0 0
since when? like 2022-01-31?
2024-01-01
[0.00 :: 8]
```

**print newly defined working hours**
```
$ tt hours:show
timer\Domain\Dto\ExpectedHoursDto Object
(
    [from] => timer\Domain\Dto\DateDto Object
    (
        [day] => 2022-01-01
    )

    [hours] => timer\Domain\Dto\WorkHoursDto Object
        (
            [monday] => 8
            [tuesday] => 8
            [wednesday] => 8
            [thursday] => 8
            [friday] => 8
            [saturday] => 0
            [sunday] => 0
        )
)
```

**start/stop tracking** arguments are optional, can use relative date from PHPs DateTime
```
$ ./tt toggle -- 8:00
timer\Domain\Dto\WorkTimeDto Object
(
    [from] => 2024-08-26 08:00:00
    [till] =>
)

$ ./tt toggle
timer\Domain\Dto\WorkTimeDto Object
(
    [from] => 2024-08-26 12:30:00
    [till] => 2024-08-26 15:18:00
)
```

**printing / balance**. summs up expected vs actual hours, printing balance
```
 $  ./tt print
2024.08.01 Thursday » 0/8
2024.08.02 Friday » 0/8
2024.08.03 Saturday » 0/0
2024.08.04 Sunday » 0/0
2024.08.05 Monday » 0/8
2024.08.06 Tuesday » 0/8
2024.08.07 Wednesday » 0/8
2024.08.08 Thursday » 0/8
2024.08.09 Friday » 0/8
2024.08.10 Saturday » 0/0
2024.08.11 Sunday » 0/0
2024.08.12 Monday » 0/8
2024.08.13 Tuesday » 0/8
2024.08.14 Wednesday » 0/8
2024.08.15 Thursday » 0/8
2024.08.16 Friday » 0/8
2024.08.17 Saturday » 0/0
2024.08.18 Sunday » 0/0
2024.08.19 Monday » 0/8
2024.08.20 Tuesday » 0/8
2024.08.21 Wednesday » 0/8
2024.08.22 Thursday » 0/8
2024.08.23 Friday » 0/8
2024.08.24 Saturday » 0/0
2024.08.25 Sunday » 0/0
2024.08.26 Monday » 6.8/8
    2024-08-26 08:00:00 - 2024-08-26 12:00:00
    2024-08-26 12:30:00 - 2024-08-26 15:18:00

6.8 // 144
```

**sick leave and other entries**
```
$ ./tt add sick 2024-08-23
$ ./tt print
[...]
2024.08.23 Friday » 8/8
    sick
2024.08.24 Saturday » 0/0
2024.08.25 Sunday » 0/0
2024.08.26 Monday » 6.8/8
    2024-08-26 08:00:00 - 2024-08-26 12:00:00
    2024-08-26 12:30:00 - 2024-08-26 15:18:00

14.8 // 144

```

**backing up to git** its advisable to initialize a git repository for the data folder. `toggle` commands will commit and push if invoked via `bin/tt`
```shell
if [[ $1 =~ 'toggle' ]]
then
    source .env

    # push if git folder
    if [[ -d "$DATA_PATH/.git" ]]
    then
        cd $DATA_PATH
        skip=. git commit -m 'entry' entries.json
        git push
    fi
fi

exit $EXIT_CODE
```
